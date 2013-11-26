<?php

require 'vendor/autoload.php';

// Authenticate via OAuth
$client = new Tumblr\API\Client(
  'bd8RFLG5vt8pP31SZruALVSE6eFWHOTVPkeUpzh3gfADwcM8I3',
  'INIbiXN9J4r0c8qSEjVDjzMI68crnYhc7bf6VyYxI34BfL7nlW',
  'ws7yh5tPwLvt1xJ2VNkqFXGj2wgb6rqI5hEGRqRczWUgvhzqE3',
  'SJZuuf9muRHkKL1YYctgGLmNBggrPY8RBNKy2v3uSs5t20uGxI'
);

// Make the request

$blogName = "umichenginvictors";
$blogs = $client->getBlogPosts($blogName, $options = null);

echo json_encode($blogs)

?>
