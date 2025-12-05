<?php
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Hotel Settings -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-hotel text-blue-500"></i>
            </div>
            <h5 class="font-semibold text-slate-800">Hotel Settings</h5>
        </div>
        <div class="p-6">
            <form>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Hotel Name</label>
                    <input type="text" value="<?= APP_NAME ?? '' ?>" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Contact Email</label>
                    <input type="email" placeholder="info@hotel.com"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Contact Phone</label>
                    <input type="text" placeholder="+62 812-3456-7890"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                    <textarea rows="3" placeholder="Hotel address..."
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bell text-amber-500"></i>
            </div>
            <h5 class="font-semibold text-slate-800">Notification Settings</h5>
        </div>
        <div class="p-6 space-y-4">
            <!-- Email Notifications -->
            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fas fa-envelope text-slate-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-700">Email Notifications</p>
                        <p class="text-xs text-slate-500">Receive email for important updates</p>
                    </div>
                </div>
                <div class="relative">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-checked:bg-blue-500 rounded-full transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                </div>
            </label>

            <!-- New Booking Alerts -->
            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fas fa-calendar-plus text-slate-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-700">New Booking Alerts</p>
                        <p class="text-xs text-slate-500">Get notified for new bookings</p>
                    </div>
                </div>
                <div class="relative">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-checked:bg-blue-500 rounded-full transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                </div>
            </label>

            <!-- Weekly Reports -->
            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fas fa-chart-bar text-slate-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-700">Weekly Reports</p>
                        <p class="text-xs text-slate-500">Receive weekly summary reports</p>
                    </div>
                </div>
                <div class="relative">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-checked:bg-blue-500 rounded-full transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                </div>
            </label>

            <!-- Check-in Reminders -->
            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fas fa-clock text-slate-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-700">Check-in Reminders</p>
                        <p class="text-xs text-slate-500">Daily reminders for check-ins</p>
                    </div>
                </div>
                <div class="relative">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-checked:bg-blue-500 rounded-full transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                </div>
            </label>
        </div>
    </div>
</div>