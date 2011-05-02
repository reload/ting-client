<?php

class TingClientScanRequest extends TingClientRequest {
  protected $field;
  protected $prefix;
  protected $numResults;
  protected $lower;
  protected $upper;
  protected $minFrequency;
  protected $maxFrequency;
  protected $output;
  protected $agency;

  protected function getRequest() {
    $this->setParameter('action', 'openScan');
    $this->setParameter('format', 'dkabm');

    $methodParameterMap = array(
      'field' => 'field',
      'prefix' => 'prefix',
      'numResults' => 'limit',
      'lower' => 'lower',
      'upper' => 'upper',
      'minFrequency' => 'minFrequency',
      'maxFrequency' => 'maxFrequency',
      'agency' => 'agency'
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    return $this;
  }

  public function getField() {
    return $this->field;
  }

  public function setField($field) {
    $this->field = $field;
  }

  public function getPrefix() {
    return $this->prefix;
  }

  public function setPrefix($prefix) {
    $this->prefix = $prefix;
  }

  public function getNumResults() {
    return $this->numResults;
  }

  public function setNumResults($numResults) {
    $this->numResults = $numResults;
  }

  public function getLower() {
    return $this->lower;
  }

  public function setLower($lower) {
    $this->lower = $lower;
  }

  public function getUpper() {
    return $this->upper;
  }

  public function setUpper($upper) {
    $this->upper = $upper;
  }

  public function getMinFrequency() {
    return $this->minFrequency;
  }

  public function setMinFrequency($minFrequency) {
    $this->minFrequency = $minFrequency;
  }

  public function getMaxFrequency() {
    return $this->maxFrequency;
  }

  public function setMaxFrequency($maxFrequency) {
    $this->maxFrequency = $maxFrequency;
  }

  public function getOutput() {
    return $this->output;
  }

  public function setOutput($output) {
    $this->output = $output;
  }

  public function getAgency() {
    return $this->agency;
  }

  public function setAgency($agency) {
    $this->agency = $agency;
  }

  public function processResponse(stdClass $response) {
    // The old API expects the result to be available at $response->terms, 
    // so let's copy it there, making sure it's an array.
    if (isset($response->term) && is_array($response->term)) {
      $response->terms = $response->term;
    }
    elseif (isset($response->term) && $response->term instanceOf stdClass) {
      $response->terms = array($response->term);
    }
    else {
      $response->terms = array();
    }

    return $response;
  }
}

