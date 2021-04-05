<?php

/**
 * Helper function for creating model with saving in DB
 *
 * @param $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function create($class, $attributes = [], $times = null)
{
    return $class::factory()->count($times)->create($attributes);
}

/**
 * Helper function for making model without saving in DB
 *
 * @param $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function make($class, $attributes = [], $times = null)
{
    return $class::factory()->count($times)->make($attributes);
}
