<?php
/**
 * サンプルのデータベース処理
 */
class admin_example_newDBA
{
    /*
     * データ追加
     * @param frm
     * @return uid
     */
    public static function insertDetail($frm)
    {
        $con = DBAccessor::getConnection();
        $sql = "INSERT t_news_detail (
            long_title,
            body,
            created_at,
            is_available
        )VALUES(
            CAST(:long_title AS CHAR) ,
            CAST(:body AS CHAR),
            NOW(),
            1
        ) ";
        $params = array(
            "long_title" => $frm["title"],
            "body"       => $frm["body"],
        );
        try{
            $con->beginTransaction();
            $con->execute($sql,$params);
            $con->commit();
            return $con->getId();
        } catch (Exception $e) {
            if(DEV_MODE) echo "\nDB ERROR : ",  $e->getMessage(), "\n";
            $con->rollback();
            return false;
        }

    }
}
