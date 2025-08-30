<?php

namespace App\Http\Controllers;

use App\Admin;
use App\AppointmentBooking;
use App\CourseCertificate;
use App\CourseEnroll;
use App\Donation;
use App\DonationLogs;
use App\EventAttendance;
use App\EventPaymentLogs;
use Illuminate\Support\Facades\Storage;
use App\Events\SupportMessage;
use App\Facades\EmailTemplate;
use App\Helpers\NexelitHelpers;
use App\Mail\BasicMail;
use App\Mail\UserEmailVeiry;
use App\Order;
use App\PaymentLogs;
use App\ProductOrder;
use App\Products;
use App\SupportTicket;
use App\SupportTicketMessage;
use App\User;
use App\MembershipCard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    const BASE_PATH = 'frontend.user.dashboard.';

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function user_index(){
        $package_orders = Order::where('user_id',$this->logged_user_details()->id)->count();
        $event_attendances = EventAttendance::where('user_id',$this->logged_user_details()->id)->count();
        $product_orders = ProductOrder::where('user_id',$this->logged_user_details()->id)->count();
        $donation = DonationLogs::where('user_id',$this->logged_user_details()->id)->count();
        $appointments = AppointmentBooking::where('user_id',$this->logged_user_details()->id)->count();
        $courses = CourseEnroll::where('user_id',$this->logged_user_details()->id)->count();
        $support_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->count();
        $membership_cards = MembershipCard::where('user_id',$this->logged_user_details()->id)->count();
        $membership_card = optional(MembershipCard::where('user_id',$this->logged_user_details()->id)->latest()->first());

        return view('frontend.user.dashboard.user-home')->with(
            [
                'package_orders' => $package_orders,
                'event_attendances' => $event_attendances,
                'product_orders' => $product_orders,
                'donation' => $donation,
                'appointments' => $appointments,
                'courses' => $courses,
                'support_tickets' => $support_tickets,
                'membership_cards' => $membership_cards,
                'membership_card' => $membership_card,
            ]);
    }
    
    public function edit_profile()
    {
        $all_countries = [
            'Afganistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antigua & Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bonaire', 'Bosnia & Herzegovina', 'Botswana', 'Brazil', 'British Indian Ocean Ter', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Canary Islands', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Channel Islands', 'Chile', 'China', 'Christmas Island', 'Cocos Island', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote DIvoire', 'Croatia', 'Cuba', 'Curacao', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Ter', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Great Britain', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guyana', 'Haiti', 'Hawaii', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'Indonesia', 'India', 'Iran', 'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea North', 'Korea South', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malaysia', 'Malawi', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Midway Islands', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Nambia', 'Nauru', 'Nepal', 'Netherland Antilles', 'Netherlands (Holland, Europe)', 'Nevis', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Norway', 'Oman', 'Pakistan', 'Palau Island', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Phillipines', 'Pitcairn Island', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Republic of Montenegro', 'Republic of Serbia', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'St Barthelemy', 'St Eustatius', 'St Helena', 'St Kitts-Nevis', 'St Lucia', 'St Maarten', 'St Pierre & Miquelon', 'St Vincent & Grenadines', 'Saipan', 'Samoa', 'Samoa American', 'San Marino', 'Sao Tome & Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Tahiti', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad & Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks & Caicos Is', 'Tuvalu', 'Uganda', 'United Kingdom', 'Ukraine', 'United Arab Erimates', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City State', 'Venezuela', 'Vietnam', 'Virgin Islands (Brit)', 'Virgin Islands (USA)', 'Wake Island', 'Wallis & Futana Is', 'Yemen', 'Zaire', 'Zambia', 'Zimbabwe'
        ];
        return view(self::BASE_PATH.'edit-profile')->with(['user_details' => $this->logged_user_details(), 'all_countries' => $all_countries]);
    }
    
    public function membership_cards(){
        // Mengambil kartu anggota terbaru untuk pengguna yang sedang login
        $membership_card = MembershipCard::where('user_id', $this->logged_user_details()->id)->latest()->first();

        // Mengambil jumlah total kartu untuk tampilan dashboard
        $membership_cards = MembershipCard::where('user_id', $this->logged_user_details()->id)->count();

        return view(self::BASE_PATH.'membership-cards')->with([
            'membership_card' => $membership_card,
            'membership_cards' => $membership_cards,
        ]);
    }
    
    public function user_email_verify_index(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }
        if (empty($user_details->email_verify_token)){
            User::find($user_details->id)->update(['email_verify_token' => \Str::random(8)]);
            $user_details = User::find($user_details->id);
            try {
                Mail::to($user_details->email)->send(new BasicMail(EmailTemplate::userVerifyMail($user_details)));
            }catch (\Exception $e){
                //
            }
        }
        return view('frontend.user.email-verify');
    }

    public function reset_user_email_verify_code(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }

        try {
            Mail::to($user_details->email)->send(new BasicMail(EmailTemplate::userVerifyMail($user_details)));
        }catch (\Exception $e){
            return redirect()->route('user.email.verify')->with(['msg' => $e->getMessage(),'type' => 'danger']);
        }

        return redirect()->route('user.email.verify')->with(['msg' => __('Resend Verify Email Success'),'type' => 'success']);
    }

    public function user_email_verify(Request $request){
        $this->validate($request,[
            'verification_code' => 'required'
        ],[
            'verification_code.required' => __('verify code is required')
        ]);
        $user_details = Auth::guard('web')->user();
        $user_info = User::where(['id' =>$user_details->id,'email_verify_token' => $request->verification_code])->first();
        if (empty($user_info)){
            return redirect()->back()->with(['msg' => __('your verification code is wrong, try again'),'type' => 'danger']);
        }
        $user_info->email_verified = 1;
        $user_info->save();
        return redirect()->route('user.home');
    }

    public function user_profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'gelar' => 'nullable|string|max:191',
            'institusi' => 'nullable|string|max:191',
            'nidn' => 'nullable|string|max:191',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'name.' => __('name is required'),
            'email.required' => __('email is required'),
            'email.email' => __('provide valid email'),
        ]);

        $user = User::findOrFail(Auth::guard()->user()->id);
        $image_name = $user->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image_path = public_path('assets/uploads/profile/' . $image_name);

            // Hapus gambar lama jika ada
            if ($user->image && File::exists(public_path('assets/uploads/profile/' . $user->image))) {
                File::delete(public_path('assets/uploads/profile/' . $user->image));
            }

            // Simpan gambar baru ke direktori yang benar
            $image->move(public_path('assets/uploads/profile'), $image_name);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'address' => $request->address,
            'gelar' => $request->gelar,
            'institusi' => $request->institusi,
            'nidn' => $request->nidn,
            'image' => $image_name,
        ]);

        return redirect()->back()->with(['msg' => __('Profile Update Success'), 'type' => 'success']);
    }

    public function user_password_change(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ],
        [
            'old_password.required' => __('Old password is required'),
            'password.required' => __('Password is required'),
            'password.confirmed' => __('password must have be confirmed')
        ]
        );

        $user = User::findOrFail(Auth::guard()->user()->id);

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::guard('web')->logout();

            return redirect()->route('user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function download_file($id){
        $product_details = Products::find($id);
        $product_success_orders = ProductOrder::where(['user_id' => Auth::guard('web')->user()->id ,'payment_status' => 'complete'])->orderBy('id','DESC')->paginate(10);
        $downloads = [];
        if (!empty($product_success_orders)){
            foreach ($product_success_orders as $order){
                $cart_items = unserialize($order->cart_items);
                foreach ($cart_items as $product){
                    if ($product['id'] == $id){
                        //check this user purchased this item or not
                        if (file_exists('assets/uploads/downloadable/'.$product_details->downloadable_file)){
                            $temp_file = asset('assets/uploads/downloadable/'.$product_details->downloadable_file);
                            $file = new Filesystem();
                            $file->copy($temp_file, 'assets/uploads/downloadable/'.\Str::slug($product_details->title).'.zip');
                            return response()->download('assets/uploads/downloadable/'.\Str::slug($product_details->title).'.zip')->deleteFileAfterSend(true);
                        }
                    }
                }
            }
        }
        return redirect()->route('user.home');
    }

    public function package_order_cancel(Request $request){
        $this->validate($request,[
            'order_id' => 'required'
        ]);
        $order_details = Order::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        $payment_log = PaymentLogs::where('order_id',$request->order_id)->first();

        //send mail to admin
        $order_page_form_mail =  get_static_option('order_page_form_mail');
        $order_mail = $order_page_form_mail ? $order_page_form_mail : get_static_option('site_global_email');
        $order_details->status = 'cancel';
        $order_details->save();
        //send mail to customer
        $data['subject'] = __('one of your package order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your package order ').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user');

        //send mail while order status change
        try {
            Mail::to($order_mail)->send(new BasicMail($data));
        }catch (\Exception $e){
            //handle error
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }
        if (!empty($payment_log)){
            //send mail to customer
            $data['subject'] = __('your order status has been cancel');
            $data['message'] = __('hello'). '<br>';
            $data['message'] .= __('your order').' #'.$order_details->id.' ';
            $data['message'] .= __('status has been changed to cancel');
            try {
                //send mail while order status change
                Mail::to($payment_log->email)->send(new BasicMail($data));
            }catch (\Exception $e){
                //handle error
                return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
            }

        }
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function product_order_cancel(Request $request)
    {
        $order_details = ProductOrder::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        ProductOrder::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        //send mail to admin
        $data['subject'] = __('one of your product order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your product order ').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        try {
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }

        //send mail to customer
        $data['subject'] = __('your order status has been cancel');
        $data['message'] = __('hello').$order_details->billing_name. '<br>';
        $data['message'] .= __('your order').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');
        try {
            //send mail while order status change
            Mail::to($order_details->billing_email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }


        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function event_order_cancel(Request $request)
    {
        $order_details = EventAttendance::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        EventAttendance::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);
        $event_payment_log = EventPaymentLogs::where(['attendance_id' => $request->order_id])->first();
        $admin_mail = !empty(get_static_option('event_attendance_receiver_mail')) ? get_static_option('event_attendance_receiver_mail') : get_static_option('site_global_email');
        //send mail to admin
        $data['subject'] = __('one of your event booking order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your event attendance id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        try {
            Mail::to($admin_mail)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }


        if (!empty($event_payment_log)){
            //send mail to customer
            $data['subject'] = __('your event booking has benn cancelled');
            $data['message'] = __('hello').$event_payment_log->name. '<br>';
            $data['message'] .= __('your event attendance id').' #'.$order_details->id.' ';
            $data['message'] .= __('booking status has been changed to cancel.');
            try {
                //send mail while order status change
                Mail::to($event_payment_log->email)->send(new BasicMail($data));
            }catch (\Exception $e){
                return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
            }
        }
        
         //todo: write code to increase  ticket number if status == cancel
        //update event available tickets
        $attendance_details = EventAttendance::where('id',$request->order_id)->first();
        $event_details = Events::findOrFail($attendance_details->event_id);
        $event_details->available_tickets = (int) $event_details->available_tickets + $attendance_details->quantity;
        $event_details->save();
        
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }

    public function donation_order_cancel(Request $request)
    {
        $order_details = DonationLogs::where(['id' => $request->order_id,'user_id' => Auth::guard('web')->user()->id])->first();
        DonationLogs::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        $donation_notify_mail = get_static_option('donation_notify_mail');
        $admin_mail = !empty($donation_notify_mail) ? $donation_notify_mail : get_static_option('site_global_email');

        //send mail to admin
        $data['subject'] = __('one of your donation has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your donation log id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');
        try {
            Mail::to($admin_mail)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }


        //send mail to customer
        $data['subject'] = __('your donation has benn cancelled');
        $data['message'] = __('hello').$order_details->name. '<br>';
        $data['message'] .= __('your donation log id').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');
        try {
            //send mail while order status change
            Mail::to($order_details->email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }


        return redirect()->back()->with(['msg' => __('donation Cancel'), 'type' => 'warning']);
    }

    public function product_order_view($id){

        $order_details = ProductOrder::find($id);
        if (empty($order_details)) {
            return redirect_404_page();
        }
        return view('frontend.user.dashboard.product-order-view')->with(['order_details' => $order_details]);
    }


    /**
     * @since 2.0.4
     * */
    public function package_orders(){
        $package_orders = Order::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(10);
        return view(self::BASE_PATH.'package-order')->with(['package_orders' => $package_orders]);
    }
    /**
     * @since 2.0.4
     * */
    public function product_orders()
    {
        $product_orders = ProductOrder::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(10);
        return view(self::BASE_PATH.'product-order')->with(['product_orders' => $product_orders]);
    }
    /**
     * @since 2.0.4
     * */
    public function event_booking()
    {
        $event_attendances = EventAttendance::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(10);
        return view(self::BASE_PATH.'event-booking')->with(['event_attendances' => $event_attendances]);
    }
    /**
     * @since 2.0.4
     * */
    public function donations()
    {
        $donations =  DonationLogs::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(10);
        return view(self::BASE_PATH.'donations')->with(['donation' => $donations]);
    }
    /**
     * @since 2.0.4
     * */
    public function appointment_booking()
    {
        $appointments =  AppointmentBooking::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(10);
        return view(self::BASE_PATH.'appointment-order')->with(['appointments' => $appointments]);
    }

    /**
     * @since 2.0.4
     * */
    /*public function edit_profile()
    {
        return view(self::BASE_PATH.'edit-profile')->with(['user_details' => $this->logged_user_details()]);
    }*/

    /**
     * @since 2.0.4
     * */
    public function change_password()
    {
        return view(self::BASE_PATH.'change-password');
    }

    public function appointment_order_cancel(Request $request){
        $order_details = AppointmentBooking::where(['id' => $request->order_id,'user_id' => $this->logged_user_details()->id])->first();
        AppointmentBooking::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        $admin_email = get_static_option('appointment_notify_mail') ?? get_static_option('site_global_email');
        //send mail to admin
        $data['subject'] = __('one of your booking has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your booking id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');

        try {
            Mail::to($admin_email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('booking Cancel, mail send failed'), 'type' => 'warning']);
        }

        //send mail to customer
        $data['subject'] = __('your booking has benn cancelled');
        $data['message'] = __('hello').' '.$order_details->name. '<br>';
        $data['message'] .= __('your booking id').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');
        try {
            //send mail while order status change
            Mail::to($order_details->email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('booking Cancel, mail send failed'), 'type' => 'warning']);
        }


        return redirect()->back()->with(['msg' => __('booking Cancel'), 'type' => 'warning']);
    }
    /**
     * @since 2.0.4
     * all user purchased digital products
     * */
    public function product_downloads()
    {
        $product_success_orders = ProductOrder::where(['user_id' => $this->logged_user_details()->id ,'payment_status' => 'complete'])->orderBy('id','DESC')->paginate(10);
        $downloads = [];
        if (!empty($product_success_orders)){
            foreach ($product_success_orders as $order){
                $cart_items = unserialize($order->cart_items,['class'=>false]);
                foreach ($cart_items as $product){
                    $product_details = Products::find($product['id']);
                    if (!empty($product_details->is_downloadable)){
                        if (array_key_exists($product_details->id,$downloads)){
                            $new_quantity = (int)$downloads[$product_details->id]['quantity'] + (int)$product['quantity'];
                            $downloads[$product_details->id] = [
                                'order_id' => $order->id,
                                'order_date' => $order->created_at,
                                'id' => $product_details->id,
                                'image' => $product_details->image,
                                'slug' => $product_details->slug,
                                'title' => $product_details->title,
                                'date' => $product_details->created_at,
                                'quantity' => $new_quantity,
                                'amount' => $product_details->sale_price * $new_quantity,
                                'downloadable_file' => $product_details->downloadable_file,
                                'downloadable_file_link' => $product_details->downloadable_file_link,
                            ];
                        }else{
                            $downloads[$product_details->id] = [
                                'order_id' => $order->id,
                                'order_date' => $order->created_at,
                                'image' => $product_details->image,
                                'id' => $product_details->id,
                                'slug' => $product_details->slug,
                                'title' => $product_details->title,
                                'date' => $product_details->created_at,
                                'quantity' => $product['quantity'],
                                'amount' => $product_details->sale_price * $product['quantity'],
                                'downloadable_file' => $product_details->downloadable_file,
                                'downloadable_file_link' => $product_details->downloadable_file_link,
                            ];
                        }
                    }
                }
            }
        }
        return view(self::BASE_PATH.'product-downloads')->with(['downloads' => $downloads]);
    }


    public function logged_user_details(){
        $old_details = '';
        if (empty($old_details)){
            $old_details = User::findOrFail(Auth::guard('web')->user()->id);
        }
        return $old_details;
    }

    public function course_enroll(){
        $all_enrolls = CourseEnroll::with(['certificate','course'])->where('user_id',$this->logged_user_details()->id)->paginate(10);
        return view(self::BASE_PATH.'course-order')->with([ 'all_enrolls' => $all_enrolls]);
    }


    public function course_order_cancel(Request $request){
        $order_details = CourseEnroll::where(['id' => $request->order_id,'user_id' => $this->logged_user_details()->id])->first();
        CourseEnroll::where('id',$order_details->id)->update([
            'status' => 'cancel'
        ]);

        $admin_email = get_static_option('course_notify_mail') ?? get_static_option('site_global_email');
        //send mail to admin
        $data['subject'] = __('one of your enroll has been cancelled');
        $data['message'] = __('Hello').'<br>';
        $data['message'] .= __('your course enroll id').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user.');

        try {
            Mail::to($admin_email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Enroll Cancel, mail send failed'), 'type' => 'warning']);
        }

        //send mail to customer
        $data['subject'] = __('your enroll has benn cancelled');
        $data['message'] = __('Hello').' '.$order_details->name. '<br>';
        $data['message'] .= __('your enroll id').' #'.$order_details->id.' ';
        $data['message'] .= __('status has been changed to cancel.');

        try {
            //send mail while order status change
            Mail::to($order_details->email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg' => __('Enroll Cancel, mail send failed'), 'type' => 'warning']);
        }

        return redirect()->back()->with(['msg' => __('Enroll Cancel'), 'type' => 'warning']);
    }


    public function support_tickets(){
        $all_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->paginate(10);
        return view(self::BASE_PATH.'support-tickets')->with([ 'all_tickets' => $all_tickets]);
    }

    public function support_ticket_priority_change(Request $request){
        $this->validate($request,[
            'priority' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return 'ok';
    }

    public function support_ticket_status_change(Request $request){
        $this->validate($request,[
            'status' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return 'ok';
    }
    public function support_ticket_view(Request $request,$id){
        $ticket_details = SupportTicket::findOrFail($id);
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get();
        $q = $request->q ?? '';
        return view(self::BASE_PATH.'view-ticket')->with(['ticket_details' => $ticket_details,'all_messages' => $all_messages,'q' => $q]);
    }

    public function support_ticket_message(Request $request){
        $this->validate($request,[
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'user_id' => Auth::guard('web')->id(),
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        event(new SupportMessage($ticket_info));

        return back()->with(NexelitHelpers::settings_update(__('Message send')));
    }

    public function generate_event_ticket(Request $request){
        $attendance_details = EventAttendance::where(['id' => $request->id,'user_id' => $this->logged_user_details()->id])->first();
        if (empty($attendance_details)) {
            return redirect_404_page();
        }

        $payment_log = EventPaymentLogs::where(['attendance_id' => $request->id])->first();
        $qr_text = 'attendance_id:'.$payment_log->attendance_id.',billing_name:'.$payment_log->name.'.,billing_email:'.$payment_log->email.',ticket_quantity:'.$attendance_details->quantity.',ticket_price: '.amount_with_currency_symbol($attendance_details->event_cost,true).',ticket_subtotal: '.amount_with_currency_symbol((int) $attendance_details->event_cost * (int)$attendance_details->quantity,true).',payment_status:'.$payment_log->status.',booking_status:'.$attendance_details->status;
        $file_name ='assets/uploads/event-qr-code/envt-att-'.$request->id.'.png';
        \QrCode::size(250)
            ->format('png')
            ->generate($qr_text,$file_name);
        $pdf = PDF::loadView('ticket.event-ticket', ['attendance_details' => $attendance_details, 'payment_log' => $payment_log,'user_details' => $this->logged_user_details(),'file_name' => $file_name]);
        return $pdf->download('event-attendance-ticket'.Str::random(16).'.pdf');
    }

    public function course_certificate(Request $request){
        $this->validate($request,[
           'course_id' => 'required'
        ]);

        // todo: check enrollment
       $course_enroll = CourseEnroll::where(['course_id' => $request->course_id, 'user_id' => auth('web')->id(),'payment_status' => 'complete'])->first();
       abort_if(is_null($course_enroll),404);
        // todo: create new certificate entry
        CourseCertificate::updateOrCreate([
            'course_id' => $request->course_id,
            'user_id' => auth('web')->id()
        ],[
            'course_id' => $request->course_id,
            'user_id' => auth('web')->id()
        ]);

        return back()->with(['msg' => __('Your Request Has Been Send!!'),'type' => 'success']);
    }

    public function course_certificate_download($id){

        $course_certificate = CourseCertificate::with(['course','user'])->find($id);
        abort_if(is_null($course_certificate),404);

        $course_enroll = CourseEnroll::where(['course_id' => $course_certificate->course_id, 'user_id' => auth('web')->id(),'payment_status' => 'complete'])->first();
        abort_if(is_null($course_enroll),404);

        $pdf = PDF::loadView('certificate.course', ['course_certificate' => $course_certificate])->setPaper('a4', 'landscape');
        return $pdf->download('certificate'.Str::random(10).'.pdf');
    }
}