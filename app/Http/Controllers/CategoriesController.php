<?php

namespace App\Http\Controllers;

use App\Model\Closingreason;
use App\Model\Status;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helpers\LaravelVersion;
use App\Model\Category;
use App\Model\Tag;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::with('closingReasons')->with('tags')->get();

        return view('panichd::admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $status_lists = $this->Statuses();
		
		return view('panichd::admin.category.create', compact('status_lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        list($request, $reason_rules, $reason_messages, $a_reasons) = $this->add_reasons_to($request);
		
		list($request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update) = $this->add_tags_to($request);
		
		// Do Laravel validation	
		$this->do_validate($request, array_merge($reason_rules, $tag_rules), array_merge($reason_messages, $tag_messages));

        $category = new Category();
        
		$category->name = $request->name;
		$category->color = $request->color;		
		$category = $this->category_email_fields($request, $category);		
		$category->create_level = $request->create_level;
		
		$category->save();

		$this->sync_reasons($request, $category, $a_reasons);
		
        $this->sync_category_tags($request, $category, $a_tags_new, $a_tags_update);

        Session::flash('status', trans('panichd::lang.category-name-has-been-created', ['name' => $request->name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\App\Http\Controllers\CategoriesController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return 'All category related agents here';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = Category::with([
			'tags'=> function ($q) {
				$q->withCount('tickets');
			}
		])->with('closingReasons.status')->findOrFail($id);
		
		$status_lists = $this->Statuses();

        return view('panichd::admin.category.edit', compact('category', 'status_lists'));
    }
	
	 /**
     * Returns statuses list
     * Decouple it with list()
     *
     * @return array
     */
    protected function Statuses()
    {

        $statuses = Cache::remember('panichd::statuses', 60, function() {
            return Status::all();
        });

        if (LaravelVersion::min('5.3.0')) {
            return $statuses->pluck('name', 'id');
        } else {
            return $statuses->lists('name', 'id');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {		
		list($request, $reason_rules, $reason_messages, $a_reasons) = $this->add_reasons_to($request);
		
		list($request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update) = $this->add_tags_to($request);
		
		// Do Laravel validation
		$this->do_validate($request, array_merge($reason_rules, $tag_rules), array_merge($reason_messages, $tag_messages));
		
        $category = Category::findOrFail($id);		

        $category->name = $request->name;
		$category->color = $request->color;
		$category = $this->category_email_fields($request, $category);		
		$category->create_level = $request->create_level;
		
		$category->save();

		$this->sync_reasons($request, $category, $a_reasons);
		
        $this->sync_category_tags($request, $category, $a_tags_new, $a_tags_update);

        Session::flash('status', trans('panichd::lang.category-name-has-been-modified', ['name' => $request->name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\App\Http\Controllers\CategoriesController@index');
    }

	/**
     * Adds reason fields to $request
     *
     * @param Request $request
     *
     * Return Array
     */
    protected function add_reasons_to($request)
    {        
        $reason_rules = $reason_messages = $a_new = $a_update = $a_delete = [];
		$regex_text = trans('panichd::lang.regex-text-inline');
		
		$min_chars = "5";
		
		if ($request->exists('reason_ordering')){			
			foreach ($request->input('reason_ordering') as $ordering=>$i){
				if ($request->has('jquery_delete_reason_'.$i)){
					$a_delete[] = $request->input('jquery_reason_id_'.$i);
				}elseif($request->has('jquery_reason_id_'.$i)) {
                
					$reason = [
						'ordering'=>$ordering
					];
					if ($request->exists('jquery_reason_text_'.$i)){
						$reason['text'] = $request->input('jquery_reason_text_'.$i);
						$reason_rules['jquery_reason_text_'.$i] = "required|min:$min_chars|regex:".$regex_text;

						// Reason message
						$reason_messages['jquery_reason_text_'.$i.'.required'] = trans('panichd::admin.category-reason-is-empty', ['number' => $i+1]);					
						$reason_messages['jquery_reason_text_'.$i.'.min'] = trans('panichd::admin.category-reason-too-short', ['number' => $i+1, 'name'=>$reason['text'], 'min' => $min_chars]);
					}

					if ($request->exists('jquery_reason_status_id_'.$i)){
						$reason['status_id'] = $request->input('jquery_reason_status_id_'.$i);
						$reason_rules['jquery_reason_status_id_'.$i] = "required|exists:statuses,id";

						// Reason message
						$reason_messages['jquery_reason_status_id_'.$i.'.required'] = trans('panichd::admin.category-reason-no-status', ['number' => $i+1,'name'=>$reason['text']]);
					}				
					
					if ($request->input('jquery_reason_id_'.$i) == "new"){
						$a_new[] = $reason;					
					}else{
						$a_update[$request->input('jquery_reason_id_'.$i)] = $reason;
					}
				}
			}
		}
		
		$a_reasons = ['new'=>$a_new, 'update'=>$a_update, 'delete'=>$a_delete];
		
        return [$request, $reason_rules, $reason_messages, $a_reasons];
    }
	
    /**
     * Adds tag fields to $request
     *
     * @param Request $request
     *
     * Return Array
     */
    protected function add_tags_to($request)
    {        
        $tag_rules = $tag_messages = [];
		
		// Allow alphanumeric and the following: ? @ / - _
        $tag_rule = "required|regex:/^[A-Za-z0-9?@\/\-_\s]+$/";

        // Add validation for new tags like it were fields
        $a_tags_new = [];
        if ($request->input('new_tags')) {
            $i = 0;
            foreach ($request->input('new_tags') as $tag) {
                $a_tags_new[] = $tag;
                $request['tag'.++$i] = $tag;
                $tag_rules['tag'.$i] = $tag_rule;
				$tag_messages['tag'.$i.'.regex'] = trans('panichd::admin.category-tag-not-valid-format', ['tag'=>$tag]);
            }
        }

        $a_tags_update = [];
        for ($i = 0; $i < $request->input('tags_count'); $i++) {
            if (!$request->has('jquery_delete_tag_'.$i)) {
                // Add validation for renamed tags
                if ($request->exists('jquery_tag_name_'.$i) and $request->has('jquery_tag_id_'.$i)) {
                    $tag = $request->input('jquery_tag_name_'.$i);
                    $request->merge(['jquery_tag_name_'.$i=>$tag]);
                    $a_tags_update[$request->input('jquery_tag_id_'.$i)]['name'] = $tag;
                    $request['jquery_tag_name_'.$i] = $tag;
                    $tag_rules['jquery_tag_name_'.$i] = $tag_rule;
					$tag_messages['jquery_tag_name_'.$i.'.regex'] = trans('panichd::admin.category-tag-not-valid-format', ['tag'=>$tag]);
                }

                // Add colors for tag update
                if ($request->has('jquery_tag_color_'.$i)) {
                    $a_tags_update[$request->input('jquery_tag_id_'.$i)]['color'] = $request->input('jquery_tag_color_'.$i);
                }
            }
        }        

        return [$request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update];
    }
	
	/**
     * Does the request validation.
     *
     * @param Request $request
     */
	protected function do_validate($request, $rules, $reason_messages)
	{
		$rules = array_merge($rules, [
            'name'         => 'required',
            'color'        => 'required',
			'create_level' => 'required|in:1,2,3'
        ]);

		if ($request->email_scope != 'default'){
			$rules = array_merge($rules, [
				'email_name'   => 'required|string',
				'email'        => 'required|email',
			]);
		}
		
		$this->validate($request, $rules, $reason_messages);
	}
	
	/*
	 * Returns category instance with email fields updated in object
	*/
	protected function category_email_fields($request, $category)
	{
		if ($request->email_scope != 'default' and $request->has('email_name') and $request->has('email')){
			$category->email_name = $request->email_name;
			$category->email = $request->email;
			
			if ($request->email_replies == 1){
				$category->email_replies = 1;
			}else{
				$category->email_replies = 0;
			}
		}else{
			$category->email_name = null;
			$category->email = null;
			$category->email_replies = 0;
		}
		
		return $category;
	}

	/**
     * Syncs reasons for category.
     *
     * @param $request
     * @param $a_tags_new Array
     * @param $category instance of App\Model\Category
     */
    protected function sync_reasons($request, $category, $a_reasons)
    {        
		// Add new reasons
		foreach ($a_reasons['new'] as $fields) {
			$new = new Closingreason();
			$new->text = $fields['text'];
			$new->status_id = $fields['status_id'];
			$new->category_id = $category->id;
			$new->ordering = $fields['ordering'];
			$new->save();		
		}
		
		// Update reasons
        foreach ($a_reasons['update'] as $id=>$fields) {
            $reason = Closingreason::where('id', $id)->first();
			$update = false;
            if (isset($fields['text'])) {
                $reason->text = $fields['text'];
				$update = true;
            }
            if (isset($fields['status_id'])) {
                $reason->status_id = $fields['status_id'];
				$update = true;
            }
			if ($reason->ordering != $fields['ordering']){
				$reason->ordering=$fields['ordering'];
				$update = true;
			}
			
			if ($update) $reason->save();
        }
		
		// Delete marked reasons
		if ($a_reasons['delete']) Closingreason::destroy($a_reasons['delete']);
    }
	
    /**
     * Syncs tags for category instance.
     *
     * @param $request
     * @param $a_tags_new Array
     * @param $category instance of App\Model\Category
     */
    protected function sync_category_tags($request, $category, $a_tags_new, $a_tags_update )
    {
        // Update renamed tags
        foreach ($a_tags_update as $id=>$fields) {
            $tag = Tag::where('id', $id)->first();
            if (isset($fields['name'])) {
                $tag->name = $fields['name'];
            }
            if (isset($fields['color'])) {
                $a_colors = explode('_', $fields['color']);
                $tag->bg_color = $a_colors[0];
                $tag->text_color = $a_colors[1];
            }
            $tag->save();
        }

        // Get category tags
        $tags = $category->tags();
        $tags = version_compare(app()->version(), '5.3.0', '>=') ? $tags->pluck('id')->toArray() : $tags->lists('id')->toArray();

        // Detach checked tags to delete
        $a_detach = $a_rename = [];
        for ($i = 0; $i < $request->input('tags_count'); $i++) {
            if ($request->has('jquery_delete_tag_'.$i)) {
                // Exclude for sync
                $a_detach[] = $request->input('jquery_delete_tag_'.$i);
            }
        }
        if ($a_detach) {
            // Detach on categories
            $tags = array_diff($tags, $a_detach);
        }

        // Add new tags
        foreach ($a_tags_new as $tag) {
            $new = Tag::whereHas('categories', function ($q) use ($category) {
                $q->where('id', $category->id);
            })->where('name', $tag)->firstOrCreate(['name'=>$tag]);

            $tags[] = $new->id;
        }

        // Sync all category tags
        $category->tags()->sync($tags);

        // Detach deleted tags that have Tickets
        Tag::whereIn('id', $a_detach)->each(function ($tag) {
            $tag->tickets()->detach();
        });

        // Delete orphan tags (Without any related categories or tickets)
        Tag::doesntHave('categories')->doesntHave('tickets')->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        // Delete orphan tags (Without any related categories or tickets)
        Tag::doesntHave('categories')->doesntHave('tickets')->delete();

        Session::flash('status', trans('panichd::lang.category-name-has-been-deleted', ['name' => $name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\App\Http\Controllers\CategoriesController@index');
    }
}
