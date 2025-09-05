<?php


namespace App\Services\Complaint;
use App\Enums\Complaint\ComplaintStatusEnum;
use App\Models\Complaint;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ComplaintService
{
    public function create(User $customer, array $data): Complaint {
        $complaint = Complaint::create([
            'customer_id' => $customer->id,
            'subject'     => $data['subject'],
            'message'     => $data['message'],
            'status'      => ComplaintStatusEnum::Open->value,
        ]);


        $admins = User::role('admin')
            ->whereNotNull('fcm_token')
            ->get(['id', 'fcm_token']);

        foreach ($admins as $admin) {
            app(NotificationService::class)->send(
                $admin,
                'شكوى جديدة',
                'تم تسجيل شكوى جديدة بعنوان: ' . $complaint->subject,
                'complaint'
            );
        }

        return $complaint;
    }

    public function listForCustomer(User $customer): Collection {
        return Complaint::where('customer_id', $customer->id)->latest()->get();
    }

    public function showForCustomer(User $customer, Complaint $complaint): Complaint {
        abort_unless((int)$complaint->customer_id === (int)$customer->id, 403);
        return $complaint;
    }

    public function listAll(?string $status = null): LengthAwarePaginator {
        return Complaint::query()
            ->when($status, fn($q)=>$q->where('status',$status))
            ->latest()->paginate(20);
    }

    public function show(Complaint $complaint): Complaint {
        return $complaint;
    }


    public function reply(Complaint $complaint, string $reply): Complaint
    {
        $data = [
            'admin_reply' => $complaint->admin_reply
                ? $complaint->admin_reply . "\n---\n" . $reply
                : $reply,
            'replied_at'  => now(),
            'status'      => ComplaintStatusEnum::Replied->value,
        ];

        $complaint->update($data);

        $customer = $complaint->customer;
        if ($customer && $customer->fcm_token) {
            app(NotificationService::class)->send(
                $customer,
                'تم الرد على الشكوى',
                'تم الرد على شكواك بعنوان: ' . $complaint->subject,
                'complaint_reply'
            );
        }

        return $complaint->fresh();
    }

    public function resolve(Complaint $complaint): Complaint
    {
        $complaint->update([
            'status'      => ComplaintStatusEnum::Resolved->value,
        ]);
        return $complaint->refresh();
    }
}
