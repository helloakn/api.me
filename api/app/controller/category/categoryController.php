<?php 
/**
 * Developed By AKN 
 * Created Date 5-Feb-2022
 * Updated Date 5-Feb-2022
 * use from : route { localhosts/wwww/home}
 */

namespace Controller\category;

use API\providers\Request;
use API\providers\S3;
use API\Hash;
use API\providers\Validator;
use API\Schema\Database;
use API\Auth;


use Extenstions\Helper;
use API\providers\Env;

/**
 * Model */
use Model\Category;
use Model\MobileApp;
use Model\Article;

class categoryController {
    function detail(Request $request,$parameter){
        $article_link = $parameter->id;

        $categories = Category::getList();

        $cmdStringDetail = <<<QUERY
        SELECT 
            Category.id,Category.name,Category.description,Category.image,Category.created_at
        FROM Category
        WHERE Category.deleted_at IS NULL AND LOWER(Category.name)='$article_link';
        
QUERY;

        $result =  Database::fetchAllQuery($cmdStringDetail);
        

        if($result){
            $categoryDetail =  new \stdClass();
            $categoryDetail = (object)$result[0];
            


/* Start Related articles */
            $cmdStringarticles = <<<QUERY
               
                SELECT 
                    Article.id, Article.title,Article.link, Article.image, Article.intro,Article.created_at,
                    CONCAT('[',
                        GROUP_CONCAT('{',
                            '"id":"',C.id,'",'
                            '"name":"',C.name,'"'
                        ,'}')
                    ,']') categories
                FROM Article
                INNER    JOIN ArticleCategory AC 
                        On Article.id = AC.article_id
                INNER    JOIN Category C 
                        On AC.category_id = C.id
                WHERE
                    Article.deleted_at IS NULL AND
                    AC.deleted_at IS NULL AND
                    C.deleted_at IS NULL AND
                    Article.id 
                    IN (
                            SELECT Article.id 
                            FROM Article 
                            LEFT JOIN ArticleCategory ON Article.id =ArticleCategory.article_id
                            LEFT JOIN Category ON ArticleCategory.category_id = Category.id
                            WHERE LOWER(Category.name)='$article_link'
                        )

                GROUP BY Article.id, Article.title, Article.image, Article.intro
                    


QUERY;

//return $cmdStringarticles;
        $result =  Database::executeQueryPaginate($cmdStringarticles,1,4);
        $articles = [];
        foreach($result->data as $k=>$v){
            $v['categories'] = json_decode($v['categories']);
            $articles[] = $v;
        }
/* End Related articles */

            $data = array(
                "code"      => 200,
                "status"    => "success",
                "message"   => "success",
                "data"      =>  array(
                    "categoryDetail" => $categoryDetail,
                    "category" => $categories,
                    "articles"    => $articles,
                    
                )
            );
            return $data;
        }
        else{
            $data = array(
                "code"      => 300,
                "status"    => "failed",
                "message"   => "there is no data",
                "data"      => array(
                    "detail" => null,
                    "category" => $categories
                    
                )
            );
            return $data;
        }
        
           
        
    }
    
}

?>