<?php
namespace App\Entity\User;
use Core\Acl\Entity as UserAcl;

class User extends UserAcl{
    /**
     * rules: required, email, float, int, min:value, max:value, unique:Entity:field:id'
     */
    public function rules($id=''){
        if($id){
            $id=':'.$id;
        }
        return [
            'field' => 'rules'
        ];
    }
}