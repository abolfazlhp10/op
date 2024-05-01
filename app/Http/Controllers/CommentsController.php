<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request){

        $this->validate($request,[
            'user_id'=>['required'],
            'article_id'=>['required'],
            'comment'=>['required','min:5']
        ]);


        Comment::create([
            'user_id'=>$request->user_id,
            'article_id'=>$request->article_id,
            'comment'=>$request->comment
        ]);

        return response()->json(['message'=>'نظر شما با موفقيت ثبت شد و پس از تاييد قرار ميگيرد'],201);

    }

    public function changeStatus($comment_id){

        $comment=Comment::findOrFail($comment_id);

        if($comment->status==1){
            $comment->status=0;
            $comment->save();
        }else{
            $comment->status=1;
            $comment->save();
        }

        return response()->json(['message'=>'وضعيت نظر با موفقيت تغيير داده شد']);
    }

    public function update($comment_id,Request $request){
        $comment=Comment::findOrFail($comment_id);

        $this->validate($request,[
            'comment'=>['required']
        ]);

        $comment->comment=$request->comment;

        $comment->update();

        return response()->json(['message'=>'نظر با موفقيت ويرايش گرديد']);
    }

    public function destroy($comment_id){

        $comment=Comment::findOrFail($comment_id);

        $comment->delete();

        return response()->json(['message'=>'نظر با موفقيت حذف گرديد']);
    }

    public function show($article_id){

        $comments=Comment::where([['article_id',$article_id],['status',1]])->with('user')->get();



        return CommentResource::collection($comments);
    }

    public function showAllComments(){
        $comments=Comment::all();
        return $comments;
    }

    public function reply($comment_id,Request $request){

        $this->validate($request,[
            'user_id'=>['required'],
            'comment'=>['required','min:5']
        ]);

        $comment=Comment::findOrFail($comment_id);

        Comment::create([
            'user_id'=>$request->user_id,
            'article_id'=>$comment->article_id,
            'comment'=>$request->comment,
            'parent_id'=>$comment_id,
            'status'=>1
        ]);

        return response()->json(['message'=>'پاسخ شما با موفقيت ثبت شد']);
    }
}
