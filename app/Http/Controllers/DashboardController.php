<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(Request $request){
        $Query=JobVacancy::query();

        if($request->has("search")&&$request->has("filter")){
            $Query->where(function($Query) use($request){
                $Query->where('title','LIKE','%'.$request->search.'%')
                ->orWhere('location','LIKE','%'.$request->search.'%')
                ->orWhereHas('company',function($Query)use ($request){
                    $Query->where('name','LIKE','%'.$request->search.'%');
                });
            })
            ->where('type',$request->filter);

        }


        if($request->has('search') && $request->filter ==null){
            $Query->where('title','LIKE','%'.$request->search.'%')
            ->orWhere('location','LIKE','%'.$request->search.'%')
            ->orWhereHas('company',function($Query)use ($request){
                $Query->where('name','LIKE','%'.$request->search.'%');
            });

        }

        if($request->has('filter') && $request->search ==null){
                $Query->where('type',$request->filter);
        }
        $Jobs=$Query->latest()->Paginate(10)->withQueryString();
        return view('dashboard',compact('Jobs'));
    }
}
