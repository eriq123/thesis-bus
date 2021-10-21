<?php

namespace App\Repositories;

use App\Models\Bus;
use Illuminate\Support\Facades\Validator;

class BusRepository
{
    public function index()
    {
        $this->data['buses'] = Bus::all();
        return $this->data;
    }

    private function saveRequest($bus, $request)
    {
        $bus->plate_number = $request->plate_number;
        $bus->type = $request->type;
        $bus->capacity = $request->capacity;
        $bus->gcash_number = $request->gcash_number;
        $bus->save();
    }

    private function validateRequest($request, $isUpdate = false)
    {
        $rules = [
            'plate_number' => 'required|max:255',
            'type' => 'required|max:255',
            'capacity' => 'required|numeric',
            'gcash_number' => 'required|numeric',
        ];

        $errorMessages = [
            'plate_number.required' => 'Plate number field is required.',
            'type.required' => 'Bus route field is required.',
            'capacity.required' => 'Total seats field is required.',
            'capacity.numeric' => 'Total seats field must be a number.',
            'gcash_number.required' => 'Gcash Number field is required.',
            'gcash_number.numeric' => 'Gcash Number field must be a number.'

        ];

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function store($request)
    {
        $this->validateRequest($request);
        $bus = new Bus();
        $this->saveRequest($bus, $request);
    }

    public function update($request)
    {
        $this->validateRequest($request, true);
        $bus = Bus::find($request->id);
        $this->saveRequest($bus, $request);
    }

    public function destroy($id)
    {
        Bus::destroy($id);
    }
}
