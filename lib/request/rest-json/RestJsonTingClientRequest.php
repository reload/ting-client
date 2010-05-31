<?php

require_once dirname(__FILE__).'/../http/HttpTingClientRequest.php';

abstract class RestJsonTingClientRequest extends HttpTingClientRequest
{
  
  public function __construct($baseUrl)
  {
    parent::__construct($baseUrl);
  }
  
  public function parseResponse($responseString)
  {
    $response = json_decode($responseString);
    if (!$response)
    {
      throw new TingClientException('Unable to decode response as JSON: '.$responseString);
    }
    // Check if response is containing an error, then throw an exception, to prevent browser from vomit
    if (is_object($response) && is_object($response->searchResponse->error)) {
      throw new TingClientException('Unexpected JSON reponse - Error located: ' . $responseString);
    }
    if (!is_object($response))
    {
      throw new TingClientException('Unexpected JSON response: '.var_export($response, true));
    }
    return $this->parseJson($response);
  }
  
  protected static function getValue($object)
  {
    if (is_array($object))
    {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $object);
    }
    else
    {
      return self::getBadgerFishValue($object, '$');
    }
  }

  protected static function getAttributeValue($object, $attributeName)
  {
    $attribute = self::getAttribute($object, $attributeName);
    if (is_array($attribute))
    {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $attribute);
    }
    else
    {
      return self::getValue($attribute); 
    }
  }

  protected static function getAttribute($object, $attributeName)
  {
    //ensure that attribute names are prefixed with @
    $attributeName = ($attributeName[0] != '@') ? '@'.$attributeName : $attributeName;
    return self::getBadgerFishValue($object, $attributeName);
  }
  
  protected static function getNamespace($object)
  {
    return self::getBadgerFishValue($object, '@');
  }
  
  /**
   * Helper to reach JSON BadgerFish values with tricky attribute names.
   */
  protected static function getBadgerFishValue($badgerFishObject, $valueName)
  {
    $properties = get_object_vars($badgerFishObject);
    if (isset($properties[$valueName])) {
      $value = $properties[$valueName];     
      if (is_string($value))
      {
        //some values contain html entities - decode these
        $value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
      }
    	      
      return $value;
    }
    else
    {
      return NULL;      
    }
  }
  
  protected abstract function parseJson($response); 
  
}