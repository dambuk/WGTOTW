<?php

namespace dabu\Comments;
 
/**
 * Model for Comments.
 *
 */
class Comments extends \Anax\MVC\CDatabaseModel
{

	
	public function findAll($pagekey=null,$pagetype=null)
	{
	
	if (isset($pagekey) && isset($pagetype)) {
		$all = $this->query()
        ->where('pagekey = ?')
        ->andWhere('pagetype = ?')
        ->execute([$pagekey, $pagetype]);
        
        return $all;
	}
	
	else {
		parent::findAll();
    }
    }

    public function findComment($pagekey=null, $id)
    {
    if (isset($pagekey) && isset($id)) {
		$all = $this->query()
        ->where('pagekey = ?')
        ->andWhere('id = ?')
        ->execute([$pagekey, $id]);
        
        return $all;
	}
    else {	
    	parent::find($id);
    }
    }
    


}