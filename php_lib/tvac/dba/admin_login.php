<?php
/**
 * サンプルのデータベース処理
 */
class exampleDBA
{

    /*
     * 弁護士カナ検索
     * @return (array)弁護士名リスト
     */
    public static function fetch_lawyers($frm)
    {
        $con = DBAccessor::getConnection();
        $sql = "SELECT
                    lawyer_name
                FROM
                    t_training
                WHERE
                    lawyer_kana LIKE CAST( :lawyer_kana AS CHAR ) AND
                    is_deleted = 0 AND
                    is_published = 1
                ";
        $params = array();
        $params["lawyer_kana"] = "%".$frm["text"]."%";

        return $con->execute($sql,$params)->fetchAll();
    }

}
