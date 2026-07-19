<div class="bg-white rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6" style="border: 1px solid #B2BEB5;">
    @foreach (['submitted' => 'Submitted By', 'approved' => 'Approved By'] as $prefix => $heading)
        <div class="space-y-3"><h3 class="text-sm font-bold text-black">{{ $heading }}</h3>
            <div><label class="block text-sm font-medium text-black">{{ $prefix === 'submitted' ? 'Submitted' : 'Approved' }} by</label><input type="text" name="{{ $prefix }}_by" value="{{ old($prefix . '_by', $data[$prefix . '_by'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"></div>
            <div><label class="block text-sm font-medium text-black">Designation/Office</label><input type="text" name="{{ $prefix }}_designation" value="{{ old($prefix . '_designation', $data[$prefix . '_designation'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"></div>
            <div><label class="block text-sm font-medium text-black">Date</label><input type="date" name="{{ $prefix }}_date" value="{{ old($prefix . '_date', $data[$prefix . '_date'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"></div>
        </div>
    @endforeach
</div>
