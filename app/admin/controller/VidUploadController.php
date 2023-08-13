<?php
/**
 * @author lin
 * @date 2023/8/13
 * @time 16:11
 */

namespace app\admin\controller;

use app\admin\validate\VidUploadValidate;
use think\exception\ValidateException;
use think\facade\Filesystem;

class VidUploadController
{
    public function index()
    {
        return view();
    }

    public function upload() {
        $vid = request()->file('video');
        try {
            validate(VidUploadValidate::class)->check(['video' => $vid]);
        }
        catch (ValidateException $exception) {
            return api_bad_request($exception->getMessage());
        }
        $savename = Filesystem::disk('public')->putFile('videos', $vid, function ($arg) {
            return $arg->hash('sha1');
        });
        return api_created('created', [
            'vidSrc' => request()->host() . '/static/' . $savename
        ]);
    }
}