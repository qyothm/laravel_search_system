@extends('welcome')

@section('content')
<div class="container mt-5">
    <div class="tabcontent" id="tab_div1" style="display: block;margin:25px;">
        <b>FULL LISTING </b>
        <table class="table table-bordered mb-5">
            <thead>
                <tr class="table-success">
                    <th scope="col">No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Year</th>
                    <th scope="col">File</th>
                </tr>
            </thead>
            <tbody>
                @foreach($show_doc_all as $data)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->category }}</td>
                    <td>{{ $data->year }}</td>
                    <td>{{ $data->file }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{  $show_doc_all->appends(request()->input())->links('layouts.paginationlinks') }}
    </div>
    <div class="tabcontent" id="tab_div2" style="display: none;margin:25px;">
        <b>BY ALPHABET</b>
<?php
    $character = range('A', 'Z');
    echo '<ul class="pagination" style="white-space:nowrap;width:100%;">';
        foreach($character as $alphabet)
        {
            echo '<li style="display:inline;margin-right:15px;cursor: pointer;" class="alphabet" id="'.$alphabet.'">'.$alphabet.'</li>';
        }
    echo '</ul>';
?>
        <div class="result_alphabet"><div class="initial_result_alphabet">Search Using Alphabet</div></div>
    </div>
    <div class="tabcontent" id="tab_div3" style="display: none;margin:25px;">
        <b>BY YEAR</b>
<?php
        $current_year = date('Y');
        $years = range($current_year, 1900); ?>
        <select id="year" name="year" style="width:10%;">
          <option value="">Select Year</option>
          <?php
          foreach ($years as $year) {
              $selected = ($year == $current_year) ? 'selected' : '';
              echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
          }
?>
</select>
<p></p>
<div class="result_year"><div class="initial_result_year">Search Using Year</div></div>
    </div>
    <div class="tabcontent" id="tab_div4" style="display: none;margin:25px;">
        <b>BY SEARCH</b>
        <p><p>
        <input type="text" style="width:400px;" placeholder="Search.." id="search_query" name="search">
        <button id="search" style="margin-top:9px; margin-left:40px;background-color: rgba(158, 156, 156, 0.829);border: none;color:black;text-align: center; width:100px; height:37px;">Search
        </button>
        <br>
        Tags:
        <div style="max-width:450px">
            <p id="tag_names">
                @foreach($tag_list as $tags)
                <label><input type="checkbox" class="taglist_checkbox" id="{{ $tags->id }}" value="{{$tags->id}}"><span>{{$tags->tag}}</span></label>
                @endforeach
            </p>
            {{-- <div class="form-group">
                <select id="tag_names" name="tag_names[]" multiple >
                @foreach($tag_list as $tags)
                <option class="taglist_checkbox" id="{{ $tags->id }}" value="{{$tags->id}}">{{$tags->tag}}</option>
                    @endforeach
                </select>
            </div> --}}
        </div>
    </div>
    <div class="result_tags"><div class="initial_result_tags">Use Search or Tags</div></div>
@endsection
