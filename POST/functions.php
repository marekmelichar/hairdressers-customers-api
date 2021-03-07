<?php

// get request for fetch all customers:
// https://hairdressers.marekmelichar.cz/wp-json/hairdressers/v1/customers

add_action('rest_api_init', function () {
  register_rest_route('hairdressers/v1', '/customers', array(
    'methods' => 'POST',
    'callback' => 'customers_post_func',
    'args' => array(
      
    ),
    'permission_callback' => function () {
      // return current_user_can( 'edit_others_posts' );
      return true;
    }
  ));
});

function customers_post_func($data)
{
  global $wpdb;

  $data = file_get_contents('php://input');
  $json = json_decode($data);

  $result_query = $wpdb->insert('customers', array(
      'name' => $json->name,
      'color' => $json->color,
      'colorCode' => $json->colorCode,
      'price' => $json->price,
      'comments' => $json->comments,
  ));

  $response = (object) [
    'row_inserted' => $result_query
  ];
  
  return new WP_REST_Response( $response, 200 );

  wp_die(); // this is required to terminate immediately and return a proper response
}