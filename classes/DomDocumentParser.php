<?php
  class DomDocumentParser{

    private $doc;

    public function __construct($url) {

      $options = array(
        "http"=>array(
          'method'=>"GET",
          'header'=>"User-Agent: scoopBot/0.1\n"
          )
      );

      $content = stream_context_create($options);
      $this->doc = new DomDocument();
      @$this->doc->loadHTML(file_get_contents($url, false, $content));
    }

    public function getLinks() {
      return $this->doc->getElementsByTagName("a");
    }

    public function getTitles() {
      return $this->doc->getElementsByTagName("title");
    }

    public function getMetaTags() {
      return $this->doc->getElementsByTagName("meta");
    }

    public function getImages() {
      return $this->doc->getElementsByTagName("img");
    }
  }
?>