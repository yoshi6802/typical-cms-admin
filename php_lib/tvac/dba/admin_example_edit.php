<?php
/**
 * サンプルのデータベース処理
 */
class admin_example_editDBA
{
    /*
     * 詳細データ取得
     */
    public static function fetchDetail($uid)
    {
        $con = DBAccessor::getConnection();
        $sql = "SELECT
                    t_news_detail_uid,
                    long_title,
                    body
                FROM
                    tvac_db.t_news_detail
                WHERE
                    1 = 1
                    AND t_news_detail_uid = CAST(:uid AS UNSIGNED)
                    AND is_deleted = 0
                    AND is_available = 1
        ";
        $params = array();
        $params["uid"] = $uid;
        return $con->execute($sql,$params)->fetch();

    }

    /*
     * データ更新
     */
    public static function updateDetail($frm)
    {
        $con = DBAccessor::getConnection();
        $sql = "UPDATE t_news_detail
                SET
                    long_title = CAST(:long_title AS CHAR) ,
                    body = CAST(:body AS CHAR),
                    updated_at = NOW()
                WHERE
                    1 = 1
                    AND t_news_detail_uid = CAST(:uid AS UNSIGNED)
                    AND is_deleted = 0
                    AND is_available = 1
        ";
        $params = array(
            "long_title" => $frm["title"],
            "body"       => $frm["body"],
            "uid"        => $frm["uid"]
        );
        try{
            $con->beginTransaction();
            $con->execute($sql,$params);
            $con->commit();
            return true;
        } catch (Exception $e) {
            if(DEV_MODE) echo "\nDB ERROR : ",  $e->getMessage(), "\n";
            $con->rollback();
            return false;
        }

    }
}
