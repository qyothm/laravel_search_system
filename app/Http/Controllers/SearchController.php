<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documents;
use App\Models\Taglist;

class SearchController extends Controller
{
    public function showDocument()
    {
        $get_doc = New Documents;
        $show_doc_all = $get_doc->getAllDocuments(10, 'show_doc_all');
        $show_doc_paginate = $get_doc->getDocumentsPaginate('Comedy', 10, 'show_doc_paginate');
        $show_doc_by_year = $get_doc->getDocumentsOrderBy('year', 10, 'show_doc_by_year');

        //Ambil dari model taglist
        $get_taglist = New taglist();
        $tag_list = $get_taglist->get();

        $view['tag_list'] = $tag_list;
        $view['show_doc_all'] = $show_doc_all;
        $view['show_doc_paginate'] = $show_doc_paginate;
        $view['show_doc_by_year'] = $show_doc_by_year;
        return view('layouts.index')->with($view);
    }

    public function download(Request $request,$file)
    {
        return response()->download(public_path('files/'.$file));
    }

    public function year(Request $request, $year,$page){
        $recordPerPage = 10;
        $pageIndex = '';
        $output = '';

        if(isset($page)){
          $pageIndex = $page;
        } else {
          $pageIndex = 1;
        }

        $start_from = ($pageIndex - 1)*$recordPerPage;

        $data = Documents::select('name','category','year','file')
                ->where('year', $year)
                ->offset($start_from)
                ->limit($recordPerPage)
                ->get();
        if($data->isNotEmpty()){
          $output .= "<table class='table table-bordered mb-5'>
                        <thead>
                        <tr class='table-success'>
                            <th scope='col'>#</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Category</th>
                            <th scope='col'>Year</th>
                            <th scope='col'>File</th>
                        </tr>
                    </thead>";
          foreach( $data as $row)
                {
                    $output .= '
                            <tbody>
                                <tr>
                                    <th scope="row">'.$row->id.'</th>
                                    <td>'.$row->name.'</td>
                                    <td>'.$row->category.'</td>
                                    <td>'.$row->year.'</td>
                                    <td><a href="'.url("/download",$row->file).'"><i class="fa fa-file-pdf-o" style="color:red"></i></a></td>
                                </tr>
                            </tbody>
                    ';
                }
          $output .="</table>";
          $total_records = Documents::select('name','year','file')
                            ->where('year', $year)
                            ->count();
            $total_pages = ceil($total_records/$recordPerPage);
            $output .="<div style='margin-top:20px; margin-left:40px;'><span class='prev' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&laquo;</span>";
              for($i=1; $i<=$total_pages; $i++)
              {
                $output .="<span class='pagination_year' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$i."'>".$i."</span>";
              }
              $output .="<span class='next' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&raquo;</span>";
        } else {
          $output .= "Document Not Found";
        }

        echo $output;
      }

      public function alphabet(Request $request, $alphabet,$page){
        $recordPerPage = 10;
        $pageIndex = '';
        $output = '';

        if(isset($page)){
          $pageIndex = $page;
        } else {
          $pageIndex = 1;
        }

        $start_from = ($pageIndex - 1)*$recordPerPage;

        $data = Documents::select('name','category','year','file')
                ->where('name', 'LIKE', $alphabet.'%')
                ->offset($start_from)
                ->limit($recordPerPage)
                ->get();
        if($data->isNotEmpty()){
          $output .= "<table class='table table-bordered mb-5'>
                        <thead>
                        <tr class='table-success'>
                            <th scope='col'>#</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Category</th>
                            <th scope='col'>Year</th>
                            <th scope='col'>File</th>
                        </tr>
                    </thead>
          ";
          foreach( $data as $row)
                {
                    $output .= '
                    <tbody>
                        <tr>
                            <th scope="row">'.$row->id.'</th>
                            <td>'.$row->name.'</td>
                            <td>'.$row->category.'</td>
                            <td>'.$row->year.'</td>
                            <td><a href="'.url("/download",$row->file).'"><i class="fa fa-file-pdf-o" style="color:red"></i></a></td>
                        </tr>
                    </tbody>
                    ';
                }
          $output .="</table>";
          $total_records = Documents::select('name','category','year','file')
                          ->where('name', 'LIKE', $alphabet.'%')
                          ->count();
          $total_pages = ceil($total_records/$recordPerPage);
          $output .="<div style='margin-top:20px; margin-left:40px;'><span class='prev' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&laquo;</span>";
            for($i=1; $i<=$total_pages; $i++)
            {
              $output .="<span class='pagination_alphabet' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$i."'>".$i."</span>";
            }
            $output .="<span class='next' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&raquo;</span>";
        } else {
          $output .= "Document Not Found";
        }
        echo $output;
    }

    public function search(Request $request){
        $recordPerPage = 10;
        $pageIndex = '';
        $output = '';

        if(isset($request->page)){
          $pageIndex = $request->page;
        } else {
          $pageIndex = 1;
        }

        $start_from = ($pageIndex - 1)*$recordPerPage;

         if ($request->ajax()) {
             $doc = Documents::select('name','category','year','file')
                    ->where('name', 'LIKE', '%'.$request->search.'%')
                    ->offset($start_from)
                    ->limit($recordPerPage)
                    ->get();

            if($doc->isNotEmpty()){
              $output .= "<table class='table table-bordered mb-5'>
                            <thead>
                            <tr class='table-success'>
                                <th scope='col'>#</th>
                                <th scope='col'>Name</th>
                                <th scope='col'>Category</th>
                                <th scope='col'>Year</th>
                                <th scope='col'>File</th>
                            </tr>
                        </thead>";
              foreach( $doc as $row)
              {
                  $output .= '
                            <tbody>
                            <tr>
                                <th scope="row">'.$row->id.'</th>
                                <td>'.$row->name.'</td>
                                <td>'.$row->category.'</td>
                                <td>'.$row->year.'</td>
                                <td><a href="'.url("/download",$row->file).'"><i class="fa fa-file-pdf-o" style="color:red"></i></a></td>
                            </tr>
                        </tbody>
                  ';
              }
              $output .="</table>";
              $total_records = Documents::select('name','category','year','file')
                              ->where('name', 'LIKE', '%'.$request->search.'%')
                              ->count();
              $total_pages = ceil($total_records/$recordPerPage);
              $output .="<div style='margin-top:20px; margin-left:40px;'><span class='prev' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&laquo;</span>";
              for($i=1; $i<=$total_pages; $i++)
              {
                  $output .="<span class='pagination_search' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$i."'>".$i."</span>";
              }
              $output .="<span class='next' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&raquo;</span>";
            } else {
              $output .= "Document Not Found";
            }
            echo $output;
         }
     }

     public function get_document_based_on_tag(Request $request, $id,$page){

        $recordPerPage = 10;
        $pageIndex = '';
        $output = '';

        if(isset($page)){
          $pageIndex = $page;
        } else {
          $pageIndex = 1;
        }

        $start_from = ($pageIndex - 1)*$recordPerPage;

        $data = Documents::select('name','year','file')
                ->whereRaw('id IN ( SELECT doc_id FROM documents_taglist WHERE tag_id IN ('.$id.') )')
                ->offset($start_from)
                ->limit($recordPerPage)
                ->get();
        if($data->isNotEmpty()){
          $output .= "<table class='table table-bordered mb-5'>
                        <thead>
                        <tr class='table-success'>
                            <th scope='col'>#</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Category</th>
                            <th scope='col'>Year</th>
                            <th scope='col'>File</th>
                        </tr>
                    </thead>";
          foreach( $data as $row)
                {
                    $output .= '
                            <tbody>
                                <tr>
                                    <th scope="row">'.$row->id.'</th>
                                    <td>'.$row->name.'</td>
                                    <td>'.$row->category.'</td>
                                    <td>'.$row->year.'</td>
                                    <td><a href="'.url("/download",$row->file).'"><i class="fa fa-file-pdf-o" style="color:red"></i></a></td>
                                </tr>
                            </tbody>
                    ';
                }
          $output .="</table>";
          $total_records = Documents::select('name','year','file')
                          ->whereRaw('id IN ( SELECT doc_id FROM documents_taglist WHERE tag_id IN ('.$id.') )')
                          ->count();
          if($total_records > 0) {
            $total_pages = ceil($total_records/$recordPerPage);
            $output .="<div style='margin-top:20px; margin-left:40px;'><span class='prev' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&laquo;</span>";

              for($i=1; $i<=$total_pages; $i++)
              {
                  $output .="<span class='pagination_link' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$i."'>".$i."</span>";
              }
              $output .="<span class='next' style='cursor:pointer; width:500px;position:center; padding: 10px;' id='".$pageIndex."_".$total_pages."'>&raquo;</span>";

          }
        } else {
          $output .= "Document Not Found";
        }
        echo $output;
     }
}
