<?php
#Exemplo:
# $Xml = new XmlData('noticias','entrada');
# $Xml->fromArray($obj->data);
# $Xml->output();

class XmlData extends XMLWriter
{
    public $rootnode = 'root';
    public $node = 'node';

    public function __construct( $rootnode = '', $node = '', $prm_xsltFilePath = '' )
    {
	$this->openMemory( );
	$this->setIndent( true );
	$this->setIndentString( ' ' );
	$this->startDocument( '1.0', 'iso-8859-1' );
	if ( $prm_xsltFilePath )
	{
	    $this->writePi( 'xml-stylesheet', 'type="text/xsl" href="' . $prm_xsltFilePath . '"' );
	}
	if ( $node != '')
	{
	    $this->node = $node;
	}
	if ( $rootnode != '')
	{
	    $this->rootnode = $rootnode;
	}
	$this->startElement( $this->rootnode );
    }

    public function setElement( $prm_elementName, $prm_ElementText )
    {
	$this->startElement( $prm_elementName );
	$this->text( $prm_ElementText );
	$this->endElement( );
    }

    public function fromArray( $arrdata )
    {
	foreach ( $arrdata as $prm_array )
	{
	    $this->startElement( $this->node );
	    foreach ( $prm_array as $index => $element )
	    {
		$element = htmlentities( $element );
		$this->setElement( $index, $element );
	    }
	    $this->endElement( );
	}
    }

    public function getDocument( )
    {
	$this->endElement( );
	$this->endDocument( );
	return $this->outputMemory( );
    }

    public function output( )
    {
	header( 'Content-type: text/xml' );
	echo $this->getDocument( );
    }
}

/* end file */