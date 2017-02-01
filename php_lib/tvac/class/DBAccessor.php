<?php
/**
 *  データベースコネクションを管理する
 */
class DBAccessor {

    private static  $_ins;

    /**
     * PDOオブジェクト
     * @var PDO
     */
    protected $_conn;

    public $_cryptKey;

    private function __construct() {
        global $CONFIG;
        if (empty($CONFIG)) {
            throw new Exception('CONFIG FILE is Empty!');
        }
        $dbParams = $CONFIG['DBParams'];
        $this->_conn = new PDO(
                $dbParams[0],
                $dbParams[1],
                $dbParams[2],
                $dbParams[3]);
        $this->_cryptKey = $CONFIG['CryptKey'];
    }

    /**
     *  インスタンスを取得します。
     *  １リクエスト、1トランザクションを担保するためにこのメソッドで取得します。
     */
    public static function getConnection() {

        if( !self::$_ins )
        {
            self::$_ins = new DBAccessor();
        }

        return self::$_ins;
    }

    public function beginTransaction()
    {
        $this->_conn->beginTransaction();
    }

    public function commit()
    {
        $this->_conn->commit();
    }

    public function rollback()
    {
        $this->_conn->rollback();
    }

    public function getId()
    {
        $row = $this->execute("SELECT LAST_INSERT_ID() AS id")->fetch();
        return $row['id'];
    }

    /**
     * IN句の右辺を作成します
     * プレフィックスとチェックボックス値を受け取り、:prefix_$id,・・・形式のSQLと
     * {:prefix_$id => $id} の配列を返します。
     */
    public function crtIN( $prefix, $list)
    {
        $sql   = array();
        $where = array();
        foreach($list as $e)
        {
            if($e['checked'])
            {
                $id           =  $e['id'];
                $key          =  ":{$prefix}_{$id}";
                $sql[]        =  $key;
                $where[$key]  =  $id;
            }
        }
        return array( implode(',',$sql) ,$where);
    }

    /**
     *  プレペアードステートメントを作成します。
     */
    public function execute($sql,$param=array())
    {
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute($param);
        return $stmt;
    }

    /**
     *  セレクトリスト用の年月日リストを生成します
     */
    public function getYmdList()
    {
        $d = new DateTime();
        $y = $d->format('Y');
        return array(
             array( $y-1 , $y, $y+1)
            ,array( 1,2,3,4,5,6,7,8,9,10,11,12)
            ,array( 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31)
        );
    }

    /**
     *  セレクトリスト用の曜日リストを作成します
     */
    public function getDayList()
    {
        return array( array('id' => 0,'name'=>'日')
                     ,array('id' => 1,'name'=>'月')
                     ,array('id' => 2,'name'=>'火')
                     ,array('id' => 3,'name'=>'水')
                     ,array('id' => 4,'name'=>'木')
                     ,array('id' => 5,'name'=>'金')
                     ,array('id' => 6,'name'=>'土')
                     );
    }




    /**
     *  セレクトリスト用のタイムテーブルカテゴリ(クレサラ・・・)リストを作成します
     */
    public function getTimeTableCategoryList()
    {
       return $this->execute("SELECT timetable_category_name as name ,timetable_category_uid as id
                        FROM  m_timetable_category
                        WHERE is_available=1
                        AND   is_deleted  =0
                        ORDER BY sort_order")
             ->fetchAll();
    }

    /**
     * シリアル値を発行します。
     */
    public function getReserveNumber($prefix,$targetdate)
    {
        $createdby = App::UID();
        $updatedby = App::UID();

        $params = compact('prefix', 'targetdate');
        $slip = $this->execute("
            SELECT
                 slip_number_uid
                ,seqmax
            FROM
                t_slip
            WHERE
                    prefix_type  = :prefix
                AND target_date   = :targetdate
                AND is_available = 1
                AND is_deleted   = 1
            "
            ,$params)->fetch();
        if (empty($slip)){
            $params = compact('prefix', 'targetdate', 'createdby', 'updatedby');
            $this->execute("
                INSERT INTO t_slip(
                     prefix_type
                    ,target_date
                    ,seqmax
                    ,is_available
                    ,created_by
                    ,created_at
                    ,updated_by
                    ,updated_at
                    ,is_deleted
                )values(
                     :prefix
                    ,:targetdate
                    ,0
                    ,1
                    ,:createdby
                    ,NOW()
                    ,:updatedby
                    ,NOW()
                    ,1
                )
                "
                ,$params);
            $seqmax = 0;		// 前日の深夜にバッチでゼロを採番しておくので
        }else{
            $seqmax = $slip['seqmax'] + 1;
            $slipnumberuid = $slip['slip_number_uid'];

            $params = compact('slipnumberuid', 'seqmax', 'updatedby');
            $rowCount = $this->execute("
                UPDATE
                    t_slip
                SET
                     seqmax     = :seqmax
                    ,updated_by = :updatedby
                    ,updated_at = NOW()
                WHERE
                        slip_number_uid = :slipnumberuid
                    AND is_available    = 1
                    AND is_deleted      = 1
                "
                ,$params)->rowCount();
            if ($rowCount>1){
                throw new PDOException('duplicate slip number');
            }
        }
	$dt = new DateTime($targetdate);
        return sprintf(
            '%02s%08s%03d',
            $prefix,
            $dt->format('Ymd'),
            $seqmax
            );
    }


    /**
     * 先々月以前のデータは削除
     * 例）本日 = 2014/04/10
     *  => 2014/03/01 より古いデータは論理削除
     *  ①マスクするカラム→氏名，氏名カナ，年齢，メルアド，TEL，相談内容
     *  ②changelogに「yyyy/mm/dd 自動削除」コメントを自動挿入する
     *  ③is_available　= 2 利用停止
     *  ④is_deleted = 2 削除済
     */
    public function getDeleteOldReserve()
    {
		$target_date = date("Y-m-01", strtotime("-1 month"));

        $updatedby = App::UID();

		$today = date();

        $dt = new DateTime(date());
        $changelog = $dt->format('Y/m/d 自動削除');

        $params = compact('target_date', 'changelog', 'updatedby');

        $this->execute("
            UPDATE
                t_reserve
            LEFT JOIN
                t_timetable
                ON t_timetable.timetable_uid = t_reserve.timetable_uid
            SET
                t_reserve.name = '*'
               ,t_reserve.name_kana = '*'
               ,t_reserve.age = '*'
               ,t_reserve.email = '*'
               ,t_reserve.tel = '*'
               ,t_reserve.content = '*'
               ,t_reserve.changelog = :changelog
               ,t_reserve.updated_by = :updatedby
               ,t_reserve.updated_at = NOW()
               ,t_reserve.is_available = 2
               ,t_reserve.is_deleted = 2
            WHERE
                    t_timetable.timetable_date < :target_date
                AND t_reserve.is_available = 1
                AND t_reserve.is_deleted   = 1
            "
            ,$params)->fetch();
    }

}
