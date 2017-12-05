<?php
return [
    '/'=>'HomeController@index',
    '/noticias'=>'NewsController@index',
    '/noticias/{id}'=>['NewsController@index','newsId'],
];