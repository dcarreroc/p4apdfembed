<?php
class PdfEmbedTest extends P4A_Base_Mask
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle("PDF Object Html");

        $this->build('Pdf_Embed','Pdf');
        // A Local File
        //$this->Pdf->setFile(P4A_SERVER_URL.P4A_UPLOADS_PATH."/"."Zend_Framework.pdf");
        // A Remote File
        $this->Pdf->setFile("http://blob.perl.org/books/beginning-perl/3145_Intro.pdf");
        $this->Pdf->setView('FitV');
        $this->Pdf->setZoom(50);
        $this->Pdf->setWidth(480);
        $this->Pdf->setHeight(640);

        $this->frame
                ->anchor($this->Pdf);
    }
}