<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Validator;
use Hash;

use App\User;
use App\Role;
use App\Expenses;
use App\ExpenseCategory;

class HomeController extends Controller {
    
    public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->load_header_files();

		$this->params['title'] = 'Expense Manager';

		return view('website.body.index', $this->params);
	}

	public function login(Request $request){

    	if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

    		$status = true;
	    	$message = '';
	    	$user = $this->_getRoles(Auth::user()->id);

	    }else{
	    	$status = false;
	    	$message = '<div class="alert alert-danger">Incorect email or password</div>';
	    	$user = '';
	    }

	    return response()->json([
            'status' => $status,
            'message' => $message,
            'user' => $user
        ]);
    }

    public function logout(){
    	if(Auth::check()){

    		Auth::logout();
    		return response()->json(true);
    	}
    }

    public function _getRoles($user_id){
    	$user = User::find($user_id);
    	$user->role = Role::find($user->role_id);

    	return $user;
    }

    public function _getCategories($expenses_id){
    	$expenses = Expenses::find($expenses_id);
    	$expenses->category = ExpenseCategory::find($expenses->category_id);

    	return $expenses;
    }

    public function roles($requests = null, $id = null, Request $request){

    	if($requests == 'save'){

    		$nid = $request->nid;
    		$role = !empty($nid) ? Role::find($nid) : new Role;

    		$role->name = $request->name;
    		$role->description = $request->description;

    		$validator = Validator::make($request->all(), [
				'name' => 'required'
	        ]);

    		$status1 = !empty($nid) ? 'updated' : 'saved';

	        if(!$validator->fails()){

	        	$role->save();
	        	
			    $status = true;
		        $message = '<div class="alert alert-success">Role successfully '.$status1.'.</div>';
		        $role1 = $role;

	        }else{
	        	$status = false;
	            $message = $validator->errors()->all();
	            $role1 = '';
	        }

	        return response()->json([
	            'status' => $status,
	            'status1' => $status1,
	            'message' => $message,
	            'role' => $role1
	        ]);

    	}elseif($requests == 'get-role'){

    		Auth::user()->role = Role::find(Auth::user()->role_id);

	    	return response()->json(Auth::user());

    	}elseif($requests == 'get-roles'){

    		$roles = Role::all();

    		return response()->json($roles);

    	}elseif($requests == 'delete'){

    		$role = Role::find($request->id);
    		if($role->delete()){

    			$status = true;
    			$message = 'Role successfully deleted.';
    			$role1 = $role;
    		}else{
    			$status = false;
    			$message = 'Can\'t delete this role.';
    			$role1 = '';
    		}

    		return response()->json([
	            'status' => $status,
	            'message' => $message,
	            'role' => $role1
    		]);

    	}

    }

    public function users($requests = null, $id = null, Request $request){

    	if($requests == 'save'){

    		$nid = $request->nid;
    		$user = !empty($nid) ? User::find($nid) : new User;

    		$user->role_id = $request->role_id;
    		$user->name = $request->name;
    		$user->email = $request->email;
    		$user->password = !empty($request->password) ? bcrypt($request->password) : $user->password;

    		$validator = Validator::make($request->all(), [
				'role_id' => 'required',
				'name' => 'required',
				'email' => 'required|email|unique:users,email,'.$nid,
				'password' => (!empty($nid) ? 'nullable' : 'required').'|min:6'
	        ]);

    		$status1 = !empty($nid) ? 'updated' : 'saved';

	        if(!$validator->fails()){

	        	$user->save();

			    $status = true;
		        $message = '<div class="alert alert-success">Account successfully '.$status1.'.</div>';
		        $user1 = $this->_getRoles($user->id);

	        }else{
	        	$status = false;
	            $message = $validator->errors()->all();
	            $user1 = '';
	        }

	        return response()->json([
	            'status' => $status,
	            'status1' => $status1,
	            'message' => $message,
	            'user' => $user1
	        ]);

    	}elseif($requests == 'save-profile'){
    		
    		if(Hash::check($request->current_password, Auth::user()->password)){

	    		$validator = Validator::make($request->all(), [
	    			'new_password' => 'required|min:6',
			        'confirm_password' => 'same:new_password'
		        ]);

		        if(!$validator->fails()){

		        	Auth::user()->password = bcrypt($request->new_password);
		        	Auth::user()->save();

				    $status = true;
			        $message = '<div class="alert alert-success">Profile successfully updated.</div>';

		        }else{
		        	$status = false;
		            $message = $validator->errors()->all();
		        }

		    }else{
		    	$status = false;
		        $message = ['Current password does not match.'];
		    }

	        return response()->json([
	            'status' => $status,
	            'message' => $message
	        ]);

    	}elseif($requests == 'get-user'){

    		if(Auth::check()){

	    		$status = true;
		    	$message = '';
		    	$user = $this->_getRoles(Auth::user()->id);

		    }else{
		    	$status = false;
		    	$message = '';
		    	$user = '';
		    }

		    return response()->json([
	            'status' => $status,
	            'message' => $message,
	            'user' => $user
	        ]);

    	}elseif($requests == 'get-users'){

    		$users = User::all();
    		$users->map(function($data){
    			$data->role = Role::find($data->role_id);

    			return $data;
    		});

    		return response()->json($users);

    	}elseif($requests == 'delete'){

    		$user = User::find($request->id);
    		if($user->delete()){

    			$status = true;
    			$message = 'User successfully deleted.';
    			$user1 = $user;
    		}else{
    			$status = false;
    			$message = 'Can\'t delete this user.';
    			$user1 = '';
    		}

    		return response()->json([
	            'status' => $status,
	            'message' => $message,
	            'user' => $user1
    		]);

    	}

    }

    public function expense_category($requests = null, $id = null, Request $request){

    	if($requests == 'save'){

    		$nid = $request->nid;
    		$category = !empty($nid) ? ExpenseCategory::find($nid) : new ExpenseCategory;

    		$category->name = $request->name;
    		$category->description = $request->description;

    		$validator = Validator::make($request->all(), [
				'name' => 'required'
	        ]);

    		$status1 = !empty($nid) ? 'updated' : 'saved';

	        if(!$validator->fails()){

	        	$category->save();
	        	
			    $status = true;
		        $message = '<div class="alert alert-success">Expense category successfully '.$status1.'.</div>';
		        $category1 = $category;

	        }else{
	        	$status = false;
	            $message = $validator->errors()->all();
	            $category1 = '';
	        }

	        return response()->json([
	            'status' => $status,
	            'status1' => $status1,
	            'message' => $message,
	            'category' => $category1
	        ]);

    	}elseif($requests == 'get'){

    		$category = ExpenseCategory::all();

    		return response()->json($category);

    	}elseif($requests == 'delete'){

    		$category = ExpenseCategory::find($request->id);
    		if($category->delete()){

    			$status = true;
    			$message = 'Expense category successfully deleted.';
    			$category1 = $category;
    		}else{
    			$status = false;
    			$message = 'Can\'t delete this expense category.';
    			$category1 = '';
    		}

    		return response()->json([
	            'status' => $status,
	            'message' => $message,
	            'category' => $category1
    		]);

    	}

    }

    public function expenses($requests = null, $id = null, Request $request){

    	if($requests == 'save'){

    		$nid = $request->nid;
    		$expense = !empty($nid) ? Expenses::find($nid) : new Expenses;

    		$expense->user_id = Auth::id();
    		$expense->category_id = $request->category_id;
    		$expense->amount = $request->amount;
    		$expense->date = $request->date;

    		$validator = Validator::make($request->all(), [
				'category_id' => 'required',
				'amount' => 'required',
				'date' => 'required'
	        ]);

    		$status1 = !empty($nid) ? 'updated' : 'saved';

	        if(!$validator->fails()){

	        	$expense->save();
	        	
			    $status = true;
		        $message = '<div class="alert alert-success">Expenses successfully '.$status1.'.</div>';
		        $expense1 = $this->_getCategories($expense->id);

	        }else{
	        	$status = false;
	            $message = $validator->errors()->all();
	            $expense1 = '';
	        }

	        return response()->json([
	            'status' => $status,
	            'status1' => $status1,
	            'message' => $message,
	            'expense' => $expense1
	        ]);

    	}elseif($requests == 'get'){

    		$user = $this->_getRoles(Auth::id());

    		$expense = $user->role->name == 'admin' ? Expenses::all() : Expenses::where('user_id', Auth::id())->get();
    		$expense->map(function($data){
    			return $data->category = ExpenseCategory::find($data->category_id);
    		});

    		return response()->json($expense);

    	}elseif($requests == 'delete'){

    		$expense = Expenses::find($request->id);
    		if($expense->delete()){

    			$status = true;
    			$message = 'Expenses successfully deleted.';
    			$expense1 = $expense;
    		}else{
    			$status = false;
    			$message = 'Can\'t delete this expenses.';
    			$expense1 = '';
    		}

    		return response()->json([
	            'status' => $status,
	            'message' => $message,
	            'expense' => $expense1
    		]);

    	}elseif($requests == 'chart'){

            $this->user = $this->_getRoles(Auth::id());

    		$expenses = ExpenseCategory::all();
	    	$expenses->map(function($expense){
                if($this->user->role->name == 'admin'){
    	    		$expense->total = Expenses::where('category_id', $expense->id)->pluck('amount')->sum();
                }else{
                    $expense->total = Expenses::where([['user_id', Auth::id()], ['category_id', $expense->id]])->pluck('amount')->sum();
                }
	    	});

	    	return response()->json($expenses);
    	}

    }

}
