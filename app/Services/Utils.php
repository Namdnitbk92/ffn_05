<?php

namespace App\Services;

trait Utils
{

    public function getTotalNotification()
    {
        $total = 0;
        try {
            if (auth()->user()->isAdmin()) {
                $total = $this->notificationRepository->whereIn('status', ['0', '1'])->count();
            } else {
                $total = $this->notificationRepository->whereIn('status', '#')->count();
            }
        } catch (Exception $e) {
            $total = 0;
        }

        return response()->json(['total' => $total, 'status' => 'OK']);
    }

    public function getListNotifications()
    {
        $notifications = collect([]);
        $datafields = [
            'id' => trans('common.id'),
            'user_id' => trans('common.user'),
            'message' => trans('common.message'),
            'status' => trans('common.status'),
            'create_at' => trans('common.send_at_time'),
        ];

        try {
            if (auth()->user()->isAdmin()) {
                $data = $this->notificationRepository->whereIn('status', ['0', '1'])->all();
            } else {
                $data = $this->notificationRepository->whereIn('status', '#')->all();
            }

            if ($data && count($data) > 0) {
                foreach ($data as $element) {
                    $temp = [];
                    $temp['id'] = $element->id;
                    $temp['message'] = $element->message;
                    $temp['status'] = $element->status;
                    $temp['create_at'] = $element->created_at;
                    $temp['user_id'] = $element->user_id;
                    $temp['user_name'] = User::find($element->user_id)->name;
                    $notifications->push($temp);
                }
            }
            
        } catch (Exception $e) {
            return response()->json(
                ['status' => trans('common.status_errors')
            ]);
        }

        return response()->json([
            'notifications' => $notifications,
            'datafields' => $datafields,
            'status' => 'OK',
        ]);
    }

}
