<?php 
/**
 * Developed By AKN 
 * Created Date 5-Feb-2022
 * Updated Date 5-Feb-2022
 * use from : route { localhosts/wwww/home}
 */

namespace Controller\AuthorSection\article;

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
use Model\Article;
use Model\ArticleDetail;
use Model\ArticleCategory;
use Model\Author;


class articleController {
    function Add(Request $request,$parameter){
        $validator = Validator::Rule(function($validator) use ($request){
            $validator->field("author_id")->max(200)->notNull();
            $validator->field("author_id")->custom(function($validator) use ( $request){
                $id = $request->get('author_id');
                if($id){
                    $c = Author::select('id')
                    ->where('id="'.$id.'" AND deleted_at IS NULL')->getAll();
                    if(!$c){
                        $validator->setError("There is no Author.");
                    }
                }
            });

            $validator->field("title")->min(10)->max(200)->notNull();
            $validator->field("link")->min(10)->max(200)->notNull();
            $validator->field("image")->min(10)->max(200)->notNull();
            $validator->field("intro")->min(10)->max(200)->notNull();

            $validator->fields("category_id")->custom(function($validator) use (&$isValidBranch, $request){
                $category_ids = $request->get('category_id');
                if(is_array($category_ids)){
                    if(count(array_count_values($category_ids)) != count($category_ids) ){
                        $isValidBranch = false;
                        $validator->setError("Category Id is duplicated.");
                    }
                    else{
                        $cids = implode(",",$category_ids);
                        $cat = Category::select('id')->where("id in($cids) AND deleted_at IS NULL")->getAll();
                        if($cat){
                            if(count($cat)!=count($category_ids)){
                                $isValidBranch = false;
                                $validator->setError("Some of your category is valid");
                            }
                        }
                        else{
                            $isValidBranch = false;
                            $validator->setError("Invalid Category.");
                        }
                    }
                }
                else{
                    $isValidBranch = false;
                    $validator->setError("Category Id should not be null or should not be empty.");
                }
            });
        });

        if(!$validator->validate()){
            $data = array(
                "code" => 400,
                "status" => "failed",
                "data" => $validator->error()
            );
            return $data;
        }
        else{
           //return $request->get('intro');;
            $article                = new Article();
            $article->title         = $request->get('title');
            $article->link          = $request->get('link');
            $article->image         = $request->get('image');
            $article->intro         = $request->get('intro');
            $article->description   = $request->get('description');
            $article->meta_tag   = $request->get('meta_tag');
            $article->author_id     = $request->get('author_id');
            //return $article->intro  ;
            $article->save();
            //return $article;
            $categories = $request->get('category_id');
            foreach($categories as $index=>$cid){
                $articleCategory = new ArticleCategory();
                $articleCategory->category_id         = $cid;
                $articleCategory->article_id          = $article->id;
                $articleCategory->save();
            }

            //type -> 1 for image, 2 for code, 3 for text, 4 for movie.
            $articleDetails = $request->get('article_details');
            foreach($articleDetails as $index=>$particledetail){
                // if( $index ==12 ){ 
                //     return $particledetail;
                // }
                $articleDetail                      = new ArticleDetail();
                $articleDetail->article_id          = $article->id;
                $articleDetail->type                = $particledetail['type'];
                $articleDetail->asc_index           = $particledetail['asc_index'];
                $articleDetail->title               = $particledetail['title'];
                $articleDetail->value               = $particledetail['value'];
                $articleDetail->before_description  = $particledetail['before_description'];
                $articleDetail->after_description   = $particledetail['after_description'];
                $articleDetail->save();

            }
            $article->details = $articleDetails;

            return array(
                "code" => 200,
                "status" => "success",
                "message" => "Success fully retrieved",
                "data" => $article
            );
        }
        
    }
    
}

?>