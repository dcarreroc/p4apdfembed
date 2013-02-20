<?php
/**
 *  Widget to embed a pdf file inside a P4A mask.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  The latest version of Pdf_Embed can be obtained from:
 *  {@link http://kode.cl}
 *
 *  @version 0.1
 *  @author Daniel Carrero <daniel@kode.cl>
 *  @copyright Copyright (c) 2011 Daniel Carrero
 *  @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 *  Widget to embed a pdf file inside a P4A mask.
 * 
 *  @author Daniel Carrero <daniel@kode.cl>
 *  @copyright Copyright (c) 2011 Daniel Carrero
 */
class Pdf_Embed extends P4A_Widget
{
    /**
     * The file you want it to be displayed.
     * @var string
     * @access private
     */
    protected $_file = null;
    
    /**
     * The width of widget in pixels, default 800.
     * @var integer
     * @access private
     */
    protected $_width = 800;

    /**
     * The height of widget in pixels, default 600.
     * @var integer
     * @access private
     */
    protected $_height = 600;

    /**
     * The zoom to view pdf file, default 100%.
     * @var integer
     * @access private
     */
    protected $_zoom = 100;

    /**
     * The page number to be displayed, default 1.
     * @var string
     * @access private
     */
    protected $_pagenum = 1;

    /**
     * Different ways to fit the document.
     * @var array
     * @access private
     */
    protected $_views = array('Fit','FitH','FitV','FitB','FitBH','FitBV');

    /**
     * How to fit the document, should be between $_views.
     * @var string
     * @access private
     * @see $_views
     */
    protected $_view = '';

    /**
     * Array with differents display mode.
     * @var string
     * @access private
     */
    protected $_pagemodes = array('bookmarks','thumbs','none');

    /**
     * Display mode should be between $_pagemodes, default none.
     * @var string
     * @access private
     * @see $_pagemodes
     */
    protected $_pagemode = 'none';

    /**
     * Turns scrollbar on or off, default on.
     * @var integer
     * @access private
     */
    protected $_scrollbar = 1;

    /**
     * Turns toolbar on or off, default on.
     * @var integer
     * @access private
     */
    protected $_toolbar = 1;

    /**
     * Turns statusbar on or off, default on.
     * @var integer
     * @access private
     */
    protected $_statusbar = 1;

    /**
     * Turns the document messages bar on or off, default on.
     * @var integer
     * @access private
     */
    protected $_messages = 1;

    /**
     * Turns the navigation panes and tabs bar on or off, default on.
     * @var integer
     * @access private
     */
    protected $_navpanes = 1;

    /**
     * Constructor
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * Set file name to be displayed.
     * @param string The name of the document.
     * @access public
     */
    public function setFile($file)
    {
        $info = pathinfo($file);
        if($info['extension'] != 'pdf')
            throw new P4A_Exception("The file has .{$info['extension']} not .pdf", P4A_FILESYSTEM_ERROR);

        $this->_file = $file;
    }

    /**
     * Set width of widget in pixels.
     * @param integer
     * @access public
     */
    public function setWidth($w)
    {
        $this->_width = $w;
    }

    /**
     * Set height of widget in pixels.
     * @param integer
     * @access public
     */
    public function setHeight($h)
    {
        $this->_height = $h;
    }

    /**
     * Set zoom of pdf file.
     * @param integer
     * @access public
     */
    public function setZoom($z)
    {
        $this->_zoom = $z;
    }

    /**
     * Set start page to be displayed.
     * @param integer
     * @access public
     */
    public function setPageNum($n)
    {
        $this->_pagenum = $n;
    }

    /**
     * Set the fit of document
     * @param string
     * @access public
     * @see $_views
     */
    public function setView($v)
    {
        if(in_array($v, $this->_views))
            $this->_view = $v;
    }

    /**
     * Set the display mode.
     * @param string
     * @access public
     */
    public function setPageMode($m)
    {
        if(in_array($m, $this->_pagemodes))
            $this->_pagemode = $m;
    }

    /**
     * Set if the scrollbar should be displayed.
     * @param integer
     * @access public
     */
    public function setScrollbar($i)
    {
        $this->_scrollbar = $i;
    }

    /**
     * Set if the toolbar should be displayed.
     * @param integer
     * @access public
     */
    public function setToolbar($i)
    {
        $this->_toolbar = $i;
    }

    /**
     * Set if the status should be displayed.
     * @param integer
     * @access public
     */
    public function setStatusbar($i)
    {
        $this->_statusbar = $i;
    }

    /**
     * Set if the message bar should be displayed.
     * @param integer
     * @access public
     */
    public function setMessages($i)
    {
        $this->_messages = $i;
    }

    /**
     * Set if the navigation panes and tabs should be displayed.
     * @param integer
     * @access public
     */
    public function setNavpanes($i)
    {
        $this->_navpanes = $i;
    }

    /**
     * Returns the HTML rendered Widget.
     * @access public
     */
    public function getAsString()
    {
        if(!is_string($this->_file))
                throw new P4A_Exception("Please be sure you've used the function setFile(\$file_name)", P4A_FILESYSTEM_ERROR);
        if(!fopen($this->_file,'r'))
                throw new P4A_Exception("File Not Found $this->_file", P4A_FILESYSTEM_ERROR);

        $p4a = & P4A::Singleton();
        $js_file = 'js/pdfobject.js';
        $p4a->addJavascript($js_file);
        
        $id = $this->getId();
        // if not visible hide the widget
        if(!$this->isVisible()){
            return "<div id='$id' class='hidden'></div>";
        }
        $script = <<<JAVASCRIPT
        <script type='text/javascript'>
        window.onload = function (){
            var myPDF = new PDFObject({ url: '{$this->_file}',
                pdfOpenParams: {
                    zoom: {$this->_zoom},
                    view: '{$this->_view}',
                    page: {$this->_pagenum},
                    pagemode: '{$this->_pagemode}',
                    scrollbar: {$this->_scrollbar},
                    toolbar: {$this->_toolbar},
                    statusbar: {$this->_statusbar},
                    messages: {$this->_messages},
                    navpanes: {$this->_navpanes}
                }
            }).embed('{$id}');
        };
        </script>
JAVASCRIPT;
        $properties = $this->composeStringProperties();

        $header = "<div id='$id' style='width: " . $this->_width .
                "px; height: " . $this->_height .
                "px' $properties";
        if(!$this->isEnabled()){
            $header .= 'disabled="disabled" ';
        }
        $header .= '/>';

        $sReturn = null;
        $sReturn .= $script;
        $sReturn .= $header;

        return $sReturn;
    }
}