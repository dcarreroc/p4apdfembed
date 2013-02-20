<?php
class PdfEmbedApp extends P4A
{

    public function __construct()
    {
        parent::__construct();
        $this->setTitle("Testing the PDF Embed App");
        $this->openMask('PdfEmbedTest');
    }

}
