<?php
/**
 * サンプルのデータベース処理
 */
class admin_homeDBA
{
    /*
     * TVACニュース取得
     */
    public static function fetchNews()
    {
        $con = DBAccessor::getConnection();
        $sql = "SELECT
                    t_news_detail_uid,
                    t_orgazation_uid,
                    staff_memo,
                    long_title,
                    short_title,
                    redirect_url,
                    body,
                    category_id,
                    daily_category_id,
                    author_by,
                    expired_at,
                    subsidy_maximum,
                    subsidy_target,
                    publish_id,
                    summary,
                    topic_published_at,
                    pickup_id,
                    daily_published_at,
                    rss_expired_at,
                    created_at,
                    created_by,
                    updated_at,
                    updated_by,
                    is_available,
                    is_deleted,
                    deleted_at,
                    inserted_at
                FROM
                     t_news_detail
                WHERE
                    1 = 1 AND
                    is_available = 1 AND
                    is_deleted  = 0
                ORDER BY updated_at DESC
                LIMIT 50

        ";
        $params = array();

        return $con->execute($sql,$params)->fetchAll();
    }

}
