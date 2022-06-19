<?php

class Uploadr extends PHPFrodo
{

    public function __construct()
    {
        parent:: __construct();
    }

    public function welcome()
    {
        $file_dst_name = "";
        $item_id = $this->uri_segment[1];
        $dir_dest = 'app/fotos/';
        $files = array( );
        $files = $_FILES['Filedata'];

        $fileObj = array( );
        foreach ( $files as $file )
        {
            $handle = new Upload( $file );
            $handle->file_overwrite = true;
            $handle->image_convert = 'jpg';
            $handle->file_new_name_ext = 'jpg';
            if ( $handle->uploaded )
            {
                if ( $handle->image_src_x > 1300 || $handle->image_y > 1100 )
                {
                    $handle->image_resize = true;
                    $handle->image_ratio_crop = true;
                    $handle->image_x = 1000;
                    $handle->image_y = 900;
                }
                $handle->file_new_name_body = md5( uniqid( $file['name'] ) );
                $handle->Process( $dir_dest );
                if ( $handle->processed )
                {
                    $file_dst_name = $handle->file_dst_name;
                    $this->insert( 'foto' )
                            ->fields( array( 'foto_item', 'foto_url' ) )
                            ->values( array( "$item_id", "$file_dst_name" ) )
                            ->execute();
                    $last_id = mysql_insert_id();
                    //$fileObj[] = array( 'url' => "$file_dst_name", 'id' => $last_id, 'time' => time() );
                    echo json_encode( array( 'url' => preg_replace( '/\.jpg/', '', $file_dst_name ), 'id' => $last_id, 'time' => time() ) );
                }
                else
                {
                    echo json_encode( array( 'url' => "error", 'id' => '', 'time' => time() ) );
                }
            }
        }
        //echo json_encode( $fileObj );
    }

}
