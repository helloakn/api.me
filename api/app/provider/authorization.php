<?php
return [
    // 'default' => 'Admin',
    // 'Admin' =>[
    //     'userTable' => 'Admin',
    //     'userFields' => ["id","email","agent_name","phone_no"],
    //     'tokenTable' => 'Token:user_id',
    // ],
    // 'User' =>[
    //     'userTable' => 'User',
    //     'userFields' => ["id","email",'display_name'],
    //     'tokenTable' => 'Token:user_id',
    // ],
    'default' => 'Author',
    'Author' =>[
        'userTable' => 'Author',
        'userFields' => ["id","phone"],
        'tokenTable' => 'Token:author_id',
        "tokenModel" =>  'Model\Token'
    ]
];
?>