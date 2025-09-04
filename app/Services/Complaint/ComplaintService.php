<?php


namespace App\Services\Complaint;
use App\Enums\Complaint\ComplaintStatusEnum;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ComplaintService
{
    public function create(User $customer, array $data): Complaint {
        return Complaint::create([
            'customer_id' => $customer->id,
            'subject'     => $data['subject'],
            'message'     => $data['message'],
            'status'      => ComplaintStatusEnum::Open->value,
        ]);
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
            'admin_reply' => $reply,
            'replied_at'  => now(),
            'status'      => ComplaintStatusEnum::Replied->value,
        ];

        $complaint->update($data);


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
