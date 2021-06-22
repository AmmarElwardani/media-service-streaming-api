<?php

namespace App\Http\Controllers;

use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Http\Requests\VideoValidation;
use App\Jobs\Videos\ConvertForStreaming;
use App\Jobs\Videos\CreateVideoThumbnail;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Asset;
use App\Models\Channel;

class VideoController extends Controller
{
    public function upload(VideoValidation $request) {
                      
        $validated = $request->validated();
        // dd($request->file());
        // exit;
        
        dd($request->all());
        if($request->file('file')) {
            
            $videoModel = new Video();
            $videoName = time().'_'.$request->file->getClientOriginalName();
            $videoPath = $request->file('file')->storeAs('uploads', $videoName, 'public');

            $videoModel->name = time().'_'.$request->file->getClientOriginalName();
            $videoModel->video_path = 'storage/app/public/' . $videoPath;
            
            
            $videoModel->asset_id = $validated['asset_id'];
            $video = $videoModel->save();

           // $this->dispatch(new ConvertForStreaming($video));

            return response()->json([
                'message' => 'Video has been uploaded.',
                'video name' => $videoName]);
        }
      
        return response()->json(['error' => 404]);
   }

   public function index (Channel $channel){

    //
   }

   public function show(Video $video){
       
     return $video;
        
   }

   public function store (Channel $channel){

        $video = $channel->videos()->create([
            'title' => request()->title, 
            'path' => request()->video->store("channels/{$channel->id}")
        ]);

        $this->dispatch(new CreateVideoThumbnail($video));
        
        $this->dispatch(new ConvertForStreaming($video));

        return $video;
   }

   public function updateViews(Video $video){

        $video->increment('views');

        return response()->json(['view updated']);

   }

   public function update(UpdateVideoRequest $request, Video $video){

        //dd(request()->all());
        $video->update($request->only(['title', 'description']));

        return response()->json([
            'message' => 'video updated',
            'status' => 200]); 
   }

}
