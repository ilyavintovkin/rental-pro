<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display user's bookings.
     */
    public function index(Request $request): View
    {
        $query = Booking::where('user_id', Auth::id())
            ->with(['item' => function($query) {
                $query->with('category');
            }])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return view('profile.bookings', compact('bookings'));
    }

    /**
     * Create a new booking.
     */
    public function create(Request $request, Item $item)
    {
        // УБЕРИТЕ ВАЛИДАЦИЮ "after:today" для тестирования
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        \Log::info('Booking request data:', $request->all());

        try {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $days = $startDate->diffInDays($endDate) + 1;

            if (!$item->isAvailableForDates($startDate, $endDate)) {
                return response()->json([
                    'error' => 'Этот инвентарь недоступен на выбранные даты'
                ], 422);
            }

            // Проверяем доступное количество
            if ($item->available_quantity <= 0) {
                return response()->json([
                    'error' => 'Все экземпляры товара уже забронированы'
                ], 422);
            }

            \Log::info('Parsed dates:', [
                'start' => $startDate,
                'end' => $endDate,
                'days' => $days
            ]);

            // Если у вас нет метода isAvailableForDates, временно закомментируйте
            // if (!$item->isAvailableForDates($startDate, $endDate)) {
            //     return response()->json([
            //         'error' => 'Этот инвентарь недоступен на выбранные даты'
            //     ], 422);
            // }

            $booking = new Booking([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'days' => $days,
                'daily_price' => $item->price_per_day,
                'total_price' => $days * $item->price_per_day,
                'deposit_amount' => $item->deposit ?? 0,
                'status' => 'pending',
                'notes' => $request->notes ?? ''
            ]);

            $booking->save();

            \Log::info('Booking created successfully:', ['id' => $booking->id]);

            return response()->json([
                'success' => true,
                'message' => 'Бронирование создано успешно!',
                'redirect' => route('profile.bookings')
            ]);

        } catch (\Exception $e) {
            \Log::error('Booking error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Произошла ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'Нельзя отменить бронирование с текущим статусом']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Бронирование отменено успешно!');
    }

    /**
     * Show booking details.
     */
    public function show(Booking $booking): View
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['item.category', 'user']);

        return view('profile.booking-show', compact('booking'));
    }
}
