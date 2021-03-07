<?php

// get request for fetch all customers:
// https://hairdressers.marekmelichar.cz/wp-json/hairdressers/v1/customers

add_action('rest_api_init', function () {
  register_rest_route('hairdressers/v1', '/customers', array(
    'methods' => 'GET',
    'callback' => 'customers_func',
    'args' => array(
      
    ),
    'permission_callback' => function () {
      // return current_user_can( 'edit_others_posts' );
      return true;
    }
  ));
});

function customers_func($data)
{
  global $wpdb;

  $id = $_GET['id'];
  
  if($id) {
    // var_dump($id);

    $query = "SELECT * from customers WHERE id=$id;";
    $result_query = $wpdb->get_results($query);
    // var_dump($result_query);

    $response = (object) [
      'id' => (float)$result_query[0]->id,
      'name' => $result_query[0]->name,
      'color' => $result_query[0]->color,
      'colorCode' => $result_query[0]->colorCode,
      'price' => $result_query[0]->price,
      'comments' => $result_query[0]->comments
    ];

  } else {
    $query = "SELECT * from customers;";
    $result_query = $wpdb->get_results($query);
  
    $customers = array();
  
    foreach ($result_query as $customer) {
      $customers[] = (object) array(
        'id' => (float)$customer->id,
        'name' => $customer->name,
        'color' => $customer->color,
        'colorCode' => $customer->colorCode,
        'price' => $customer->price,
        'comments' => $customer->comments
      );
    }
  
    $response = (object) [
      'customers' => $customers
    ];
  }
  
  return new WP_REST_Response( $response, 200 );

  wp_die(); // this is required to terminate immediately and return a proper response
}