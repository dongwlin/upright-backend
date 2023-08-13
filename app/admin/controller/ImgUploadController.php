<?php
/**
 * @author lin
 * @date 2023/8/13
 * @time 10:30
 */

namespace app\admin\controller;

use app\admin\validate\ImgUploadValidate;
use think\exception\ValidateException;
use think\facade\Filesystem;
use think\facade\Validate;

class ImgUploadController
{
    public function index()
    {
        return view();
    }

    public function upload() {
        $img = request()->file('image');
        try {
            validate(ImgUploadValidate::class)->check(['image' => $img]);
        }
        catch (ValidateException $exception) {
            return api_bad_request($exception->getMessage());
        }
        $savename = Filesystem::disk('public')->putFile('images', $img, function ($arg) {
            return $arg->hash('sha1');
        });
        return api_created('created', [
            'imgSrc' => request()->host() . '/static/' . $savename
        ]);
    }
}