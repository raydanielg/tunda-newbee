<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\ProfilePhoto;
use App\Models\Interest;
use App\Models\Swipe;
use App\Models\UserMatch;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AppNotification;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\PremiumSubscription;
use App\Models\Payment;
use App\Models\Boost;
use App\Models\SuperLike;
use App\Models\Report;
use App\Models\Block;
use App\Models\VerificationRequest;
use App\Models\Story;
use App\Models\StoryView;
use App\Models\Call;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TundaSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedInterests();
        $this->seedAdmins();
        $this->seedUsers();
        $this->seedSwipesAndMatches();
        $this->seedConversationsAndMessages();
        $this->seedNotifications();
        $this->seedWalletsAndTransactions();
        $this->seedPremiumAndPayments();
        $this->seedBoostsAndSuperLikes();
        $this->seedStories();
        $this->seedCalls();
        $this->seedReportsAndBlocks();
        $this->seedVerificationRequests();
    }

    private function seedInterests(): void
    {
        $interests = [
            ['name' => 'Music', 'icon' => 'music'],
            ['name' => 'Travel', 'icon' => 'plane'],
            ['name' => 'Sports', 'icon' => 'basketball'],
            ['name' => 'Cooking', 'icon' => 'chef'],
            ['name' => 'Technology', 'icon' => 'code'],
            ['name' => 'Fashion', 'icon' => 'shirt'],
            ['name' => 'Photography', 'icon' => 'camera'],
            ['name' => 'Movies', 'icon' => 'film'],
            ['name' => 'Reading', 'icon' => 'book'],
            ['name' => 'Business', 'icon' => 'briefcase'],
            ['name' => 'Gaming', 'icon' => 'gamepad'],
            ['name' => 'Art', 'icon' => 'palette'],
            ['name' => 'Dancing', 'icon' => 'dance'],
            ['name' => 'Fitness', 'icon' => 'dumbbell'],
            ['name' => 'Food', 'icon' => 'restaurant'],
        ];

        foreach ($interests as $i) {
            Interest::firstOrCreate(['name' => $i['name']], ['icon' => $i['icon']]);
        }
    }

    private function seedAdmins(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tunda.app',
            'password' => Hash::make('Admin@2026'),
            'role' => 'super_admin',
            'status' => 'active',
            'gender' => 'male',
            'phone' => '+255700000001',
            'region' => 'Dar es Salaam',
            'date_of_birth' => '1990-01-15',
        ]);

        Profile::create([
            'user_id' => $superAdmin->id,
            'bio' => 'System Super Administrator — full platform access.',
            'occupation' => 'System Administrator',
            'education' => 'Masters in IT',
            'relationship_goal' => 'serious_relationship',
            'looking_for' => 'female',
            'is_verified' => true,
            'is_premium' => true,
            'profile_completion' => 100,
        ]);

        // Regular Admin
        $admin = User::create([
            'name' => 'Moderator',
            'email' => 'moderator@tunda.app',
            'password' => Hash::make('Moderator@2026'),
            'role' => 'admin',
            'status' => 'active',
            'gender' => 'female',
            'phone' => '+255700000002',
            'region' => 'Dar es Salaam',
            'date_of_birth' => '1992-05-20',
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'Content Moderator — handles reports and verifications.',
            'occupation' => 'Content Moderator',
            'education' => 'Degree in Communications',
            'relationship_goal' => 'dating',
            'looking_for' => 'male',
            'is_verified' => true,
            'profile_completion' => 100,
        ]);

        Wallet::firstOrCreate(
            ['user_id' => $superAdmin->id],
            ['balance' => 0, 'currency' => 'TZS']
        );
        Wallet::firstOrCreate(
            ['user_id' => $admin->id],
            ['balance' => 0, 'currency' => 'TZS']
        );
    }

    private function seedUsers(): void
    {
        $usersData = [
            // Females
            ['name' => 'Aisha', 'email' => 'aisha@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam', 'dob' => '2001-03-12', 'phone' => '+255712100001', 'occupation' => 'Software Engineer', 'education' => 'UDSM Graduate', 'bio' => 'Coffee lover and adventure seeker. Looking for someone to explore life with.', 'goal' => 'serious_relationship', 'looking' => 'male', 'verified' => true, 'premium' => true],
            ['name' => 'Zainab', 'email' => 'zainab@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam', 'dob' => '1999-07-22', 'phone' => '+255712100002', 'occupation' => 'Business Owner', 'education' => 'Mzumbe University', 'bio' => 'Entrepreneur with a passion for fashion. Let\'s build something great together.', 'goal' => 'marriage', 'looking' => 'male', 'verified' => true, 'premium' => false],
            ['name' => 'Rehema', 'email' => 'rehema@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam', 'dob' => '2002-11-05', 'phone' => '+255712100003', 'occupation' => 'Medical Student', 'education' => 'MUHAS', 'bio' => 'Medical student who loves reading and quiet evenings. Seeking a kind soul.', 'goal' => 'dating', 'looking' => 'male', 'verified' => false, 'premium' => false],
            ['name' => 'Neema', 'email' => 'neema@tunda.app', 'gender' => 'female', 'region' => 'Dodoma', 'dob' => '2000-09-18', 'phone' => '+255712100004', 'occupation' => 'Teacher', 'education' => 'University of Dodoma', 'bio' => 'Teacher by day, dancer by night. Life is too short for boring company.', 'goal' => 'friendship', 'looking' => 'both', 'verified' => true, 'premium' => false],
            ['name' => 'Fatuma', 'email' => 'fatuma@tunda.app', 'gender' => 'female', 'region' => 'Dar es Salaam', 'dob' => '1998-12-03', 'phone' => '+255712100005', 'occupation' => 'Marketing Manager', 'education' => 'TUM', 'bio' => 'Marketing professional who loves the beach and good conversations.', 'goal' => 'serious_relationship', 'looking' => 'male', 'verified' => true, 'premium' => true],
            ['name' => 'Halima', 'email' => 'halima@tunda.app', 'gender' => 'female', 'region' => 'Arusha', 'dob' => '2001-06-14', 'phone' => '+255712100006', 'occupation' => 'Nurse', 'education' => 'KCMC', 'bio' => 'Nurse with a big heart. Love hiking and nature walks on weekends.', 'goal' => 'serious_relationship', 'looking' => 'male', 'verified' => false, 'premium' => false],
            ['name' => 'Mariam', 'email' => 'mariam@tunda.app', 'gender' => 'female', 'region' => 'Mwanza', 'dob' => '1999-02-28', 'phone' => '+255712100007', 'occupation' => 'Lawyer', 'education' => 'UDSM Law School', 'bio' => 'Lawyer by profession, foodie by passion. Looking for genuine connection.', 'goal' => 'marriage', 'looking' => 'male', 'verified' => true, 'premium' => true],

            // Males
            ['name' => 'John', 'email' => 'john@tunda.app', 'gender' => 'male', 'region' => 'Dar es Salaam', 'dob' => '1998-04-10', 'phone' => '+255712200001', 'occupation' => 'Software Developer', 'education' => 'UDSM', 'bio' => 'Tech enthusiast who codes by day and plays guitar by night.', 'goal' => 'serious_relationship', 'looking' => 'female', 'verified' => true, 'premium' => true],
            ['name' => 'Joseph', 'email' => 'joseph@tunda.app', 'gender' => 'male', 'region' => 'Arusha', 'dob' => '1996-08-25', 'phone' => '+255712200002', 'occupation' => 'Architect', 'education' => 'Ardhi University', 'bio' => 'Architect designing dreams. Love traveling and photography.', 'goal' => 'serious_relationship', 'looking' => 'female', 'verified' => false, 'premium' => false],
            ['name' => 'James', 'email' => 'james@tunda.app', 'gender' => 'male', 'region' => 'Mwanza', 'dob' => '2000-01-30', 'phone' => '+255712200003', 'occupation' => 'Accountant', 'education' => 'University of Dodoma', 'bio' => 'Numbers guy looking for someone to count stars with.', 'goal' => 'dating', 'looking' => 'female', 'verified' => false, 'premium' => false],
            ['name' => 'David', 'email' => 'david@tunda.app', 'gender' => 'male', 'region' => 'Dar es Salaam', 'dob' => '1997-10-17', 'phone' => '+255712200004', 'occupation' => 'Doctor', 'education' => 'MUHAS', 'bio' => 'Doctor saving lives, looking for someone to share mine with.', 'goal' => 'marriage', 'looking' => 'female', 'verified' => true, 'premium' => true],
            ['name' => 'Eric', 'email' => 'eric@tunda.app', 'gender' => 'male', 'region' => 'Dodoma', 'dob' => '1999-05-22', 'phone' => '+255712200005', 'occupation' => 'Civil Engineer', 'education' => 'UDSM', 'bio' => 'Building bridges by profession, hoping to build one to your heart.', 'goal' => 'serious_relationship', 'looking' => 'female', 'verified' => false, 'premium' => false],
        ];

        foreach ($usersData as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password123'),
                'gender' => $u['gender'],
                'region' => $u['region'],
                'date_of_birth' => $u['dob'],
                'phone' => $u['phone'],
                'role' => 'user',
                'status' => 'active',
                'last_active_at' => now()->subMinutes(rand(1, 1440)),
            ]);

            $completion = 40;
            if ($u['verified']) $completion += 20;
            if ($u['premium']) $completion += 15;
            $completion += rand(10, 25);

            Profile::create([
                'user_id' => $user->id,
                'bio' => $u['bio'],
                'occupation' => $u['occupation'],
                'education' => $u['education'],
                'relationship_goal' => $u['goal'],
                'looking_for' => $u['looking'],
                'is_verified' => $u['verified'],
                'is_premium' => $u['premium'],
                'profile_completion' => min(100, $completion),
                'min_age_preference' => 18,
                'max_age_preference' => 45,
                'max_distance_km' => 50,
            ]);

            // Assign 3-5 random interests
            $user->interests()->attach(
                Interest::inRandomOrder()->limit(rand(3, 5))->pluck('id')
            );

            // Create wallet for each user
            $balance = $u['premium'] ? rand(5000, 25000) : rand(0, 5000);
            Wallet::create([
                'user_id' => $user->id,
                'balance' => $balance,
                'currency' => 'TZS',
            ]);

            // Add profile photos placeholder
            for ($i = 1; $i <= rand(2, 4); $i++) {
                ProfilePhoto::create([
                    'user_id' => $user->id,
                    'path' => "profiles/{$user->id}/photo_{$i}.jpg",
                    'position' => $i,
                    'is_primary' => $i === 1,
                ]);
            }
        }
    }

    private function seedSwipesAndMatches(): void
    {
        $females = User::where('gender', 'female')->where('role', 'user')->get();
        $males = User::where('gender', 'male')->where('role', 'user')->get();

        // Create mutual likes = matches
        $matchPairs = [
            [0, 0], // Aisha + John
            [1, 0], // Zainab + John
            [4, 3], // Fatuma + David
            [6, 1], // Mariam + Joseph
            [0, 3], // Aisha + David
            [5, 2], // Halima + James
        ];

        foreach ($matchPairs as [$fIdx, $mIdx]) {
            $female = $females[$fIdx] ?? null;
            $male = $males[$mIdx] ?? null;
            if (!$female || !$male) continue;

            // Female likes male
            Swipe::firstOrCreate([
                'swiper_id' => $female->id,
                'swiped_id' => $male->id,
                'action' => 'like',
            ]);

            // Male likes female (mutual = match)
            Swipe::firstOrCreate([
                'swiper_id' => $male->id,
                'swiped_id' => $female->id,
                'action' => 'like',
            ]);

            // Create match
            $user1Id = min($female->id, $male->id);
            $user2Id = max($female->id, $male->id);

            $match = UserMatch::firstOrCreate([
                'user1_id' => $user1Id,
                'user2_id' => $user2Id,
            ], [
                'matched_at' => now()->subHours(rand(1, 72)),
                'is_active' => true,
            ]);

            // Create conversation
            Conversation::firstOrCreate([
                'match_id' => $match->id,
            ], [
                'last_message_at' => now()->subHours(rand(1, 48)),
            ]);
        }

        // Add some one-way likes (no match)
        $allUsers = User::where('role', 'user')->get();
        $usedPairs = Swipe::all()->map(fn($s) => "{$s->swiper_id}-{$s->swiped_id}")->toArray();
        $attempts = 0;
        $added = 0;
        while ($added < 15 && $attempts < 100) {
            $attempts++;
            $swiper = $allUsers->random();
            $swiped = $allUsers->where('id', '!=', $swiper->id)->random();
            $key = "{$swiper->id}-{$swiped->id}";
            if (in_array($key, $usedPairs)) continue;
            $usedPairs[] = $key;
            Swipe::create([
                'swiper_id' => $swiper->id,
                'swiped_id' => $swiped->id,
                'action' => collect(['like', 'dislike', 'super_like'])->random(),
            ]);
            $added++;
        }
    }

    private function seedConversationsAndMessages(): void
    {
        $conversations = Conversation::all();

        foreach ($conversations as $conv) {
            $match = $conv->match;
            $user1 = User::find($match->user1_id);
            $user2 = User::find($match->user2_id);

            $messages = [
                ['sender' => $user1, 'body' => "Hey! Nice to match with you 😊", 'hours_ago' => 24],
                ['sender' => $user2, 'body' => "Hi! I was happy to see we matched too!", 'hours_ago' => 23],
                ['sender' => $user1, 'body' => "What are you looking for on here?", 'hours_ago' => 22],
                ['sender' => $user2, 'body' => "Something real. You?", 'hours_ago' => 21],
                ['sender' => $user1, 'body' => "Same! Would love to get to know you better.", 'hours_ago' => 20],
                ['sender' => $user2, 'body' => "Let's chat more and see where this goes 🙌", 'hours_ago' => 18],
            ];

            foreach ($messages as $msg) {
                Message::create([
                    'conversation_id' => $conv->id,
                    'sender_id' => $msg['sender']->id,
                    'body' => $msg['body'],
                    'type' => 'text',
                    'created_at' => now()->subHours($msg['hours_ago']),
                    'read_at' => $msg['hours_ago'] > 5 ? now()->subHours($msg['hours_ago'] - 1) : null,
                ]);
            }
        }
    }

    private function seedNotifications(): void
    {
        $users = User::where('role', 'user')->get();

        $notificationTemplates = [
            ['type' => 'match', 'title' => 'New Match!', 'body' => 'You and {name} liked each other.'],
            ['type' => 'message', 'title' => 'New Message', 'body' => '{name} sent you a message.'],
            ['type' => 'like', 'title' => 'Someone Liked You', 'body' => '{name} liked your profile.'],
            ['type' => 'super_like', 'title' => 'Super Like!', 'body' => '{name} super liked you! 💫'],
            ['type' => 'verification', 'title' => 'Verification Approved', 'body' => 'Your profile verification was approved. ✅'],
            ['type' => 'premium', 'title' => 'Premium Activated', 'body' => 'Enjoy your premium features! 💎'],
        ];

        foreach ($users as $user) {
            // 2-5 notifications per user
            $count = rand(2, 5);
            $otherUsers = User::where('role', 'user')->where('id', '!=', $user->id)->inRandomOrder()->limit($count)->get();

            for ($i = 0; $i < $count; $i++) {
                $template = $notificationTemplates[array_rand($notificationTemplates)];
                $otherUser = $otherUsers->get($i);

                AppNotification::create([
                    'user_id' => $user->id,
                    'type' => $template['type'],
                    'title' => $template['title'],
                    'body' => str_replace('{name}', $otherUser?->name ?? 'Someone', $template['body']),
                    'read_at' => $i > 1 ? now()->subHours(rand(2, 48)) : null,
                    'created_at' => now()->subHours(rand(1, 72)),
                ]);
            }
        }
    }

    private function seedWalletsAndTransactions(): void
    {
        $wallets = Wallet::all();

        foreach ($wallets as $wallet) {
            if ($wallet->balance > 0) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'credit',
                    'amount' => $wallet->balance,
                    'description' => 'Wallet top-up',
                    'reason' => 'wallet_topup',
                ]);
            }

            // Add a debit for premium users
            if ($wallet->balance > 5000) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'debit',
                    'amount' => 5000,
                    'description' => 'Premium subscription purchase',
                    'reason' => 'premium',
                ]);
            }
        }
    }

    private function seedPremiumAndPayments(): void
    {
        $premiumUsers = User::whereHas('profile', fn($q) => $q->where('is_premium', true))->get();

        foreach ($premiumUsers as $user) {
            $plan = collect(['monthly', 'quarterly', 'annual'])->random();
            $amount = match($plan) {
                'monthly' => 15000,
                'quarterly' => 40000,
                'annual' => 120000,
            };

            PremiumSubscription::create([
                'user_id' => $user->id,
                'plan' => $plan,
                'amount' => $amount,
                'currency' => 'TZS',
                'starts_at' => now()->subDays(rand(1, 60)),
                'ends_at' => now()->addDays(match($plan) {
                    'monthly' => 30,
                    'quarterly' => 90,
                    'annual' => 365,
                }),
                'status' => 'active',
                'auto_renew' => (bool) rand(0, 1),
            ]);

            Payment::create([
                'user_id' => $user->id,
                'payment_reference' => 'TND-' . strtoupper(uniqid()),
                'purpose' => 'premium',
                'amount' => $amount,
                'currency' => 'TZS',
                'method' => 'mobile_money',
                'status' => 'completed',
                'gateway_response' => ['status' => 'success', 'gateway' => 'selcom'],
            ]);
        }

        // Add some failed/pending payments
        $regularUsers = User::whereHas('profile', fn($q) => $q->where('is_premium', false))->take(3)->get();
        foreach ($regularUsers as $user) {
            Payment::create([
                'user_id' => $user->id,
                'payment_reference' => 'TND-' . strtoupper(uniqid()),
                'purpose' => collect(['boost', 'super_like', 'wallet_topup'])->random(),
                'amount' => collect([2000, 3000, 5000])->random(),
                'currency' => 'TZS',
                'method' => collect(['mobile_money', 'card'])->random(),
                'status' => collect(['pending', 'failed'])->random(),
                'gateway_response' => ['status' => 'pending'],
            ]);
        }
    }

    private function seedBoostsAndSuperLikes(): void
    {
        $users = User::where('role', 'user')->get();

        // Boosts for premium users
        foreach ($users->take(3) as $user) {
            Boost::create([
                'user_id' => $user->id,
                'starts_at' => now()->subHours(rand(1, 12)),
                'ends_at' => now()->addHours(rand(1, 12)),
                'impressions' => rand(50, 500),
                'is_active' => true,
            ]);
        }

        // Super likes
        for ($i = 0; $i < 8; $i++) {
            $swiper = $users->random();
            $target = $users->where('id', '!=', $swiper->id)->random();

            SuperLike::create([
                'user_id' => $swiper->id,
                'target_user_id' => $target->id,
                'used_at' => now()->subHours(rand(1, 48)),
            ]);
        }
    }

    private function seedStories(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users->take(6) as $user) {
            $story = Story::create([
                'user_id' => $user->id,
                'media_path' => "stories/{$user->id}/story_" . rand(1, 999) . ".jpg",
                'media_type' => 'image',
                'caption' => collect(['Good vibes only ✨', 'Weekend mood 🎉', 'Just being me 😊', 'Sunset vibes 🌅', ''])->random(),
                'expires_at' => now()->addHours(rand(1, 20)),
            ]);

            // Add some views
            $viewers = $users->where('id', '!=', $user->id)->take(rand(2, 5));
            foreach ($viewers as $viewer) {
                StoryView::create([
                    'story_id' => $story->id,
                    'viewer_id' => $viewer->id,
                    'viewed_at' => now()->subMinutes(rand(5, 300)),
                ]);
            }
        }
    }

    private function seedCalls(): void
    {
        $matches = UserMatch::with(['user1', 'user2'])->get();

        foreach ($matches->take(4) as $match) {
            $caller = collect([$match->user1, $match->user2])->random();
            $receiver = $caller->id === $match->user1_id ? $match->user2 : $match->user1;

            Call::create([
                'caller_id' => $caller->id,
                'receiver_id' => $receiver->id,
                'type' => collect(['voice', 'video'])->random(),
                'status' => collect(['completed', 'missed', 'declined'])->random(),
                'duration' => rand(10, 600),
                'started_at' => now()->subHours(rand(1, 48)),
                'ended_at' => now()->subHours(rand(0, 47)),
            ]);
        }
    }

    private function seedReportsAndBlocks(): void
    {
        $users = User::where('role', 'user')->get();

        // Reports
        $reportReasons = ['harassment', 'fake_profile', 'inappropriate_content', 'spam', 'scam'];
        for ($i = 0; $i < 5; $i++) {
            $reporter = $users->random();
            $reported = $users->where('id', '!=', $reporter->id)->random();

            Report::create([
                'reporter_id' => $reporter->id,
                'reported_id' => $reported->id,
                'reason' => $reportReasons[array_rand($reportReasons)],
                'description' => 'User reported for suspicious behavior during conversation.',
                'status' => collect(['pending', 'pending', 'pending', 'resolved', 'dismissed'])->random(),
                'resolved_by' => 1,
                'resolved_at' => now()->subHours(rand(1, 72)),
            ]);
        }

        // Blocks
        for ($i = 0; $i < 4; $i++) {
            $blocker = $users->random();
            $blocked = $users->where('id', '!=', $blocker->id)->random();

            Block::firstOrCreate([
                'blocker_id' => $blocker->id,
                'blocked_id' => $blocked->id,
            ]);
        }
    }

    private function seedVerificationRequests(): void
    {
        $unverifiedUsers = User::whereHas('profile', fn($q) => $q->where('is_verified', false))->where('role', 'user')->get();

        foreach ($unverifiedUsers->take(4) as $user) {
            VerificationRequest::create([
                'user_id' => $user->id,
                'id_front_path' => "verifications/{$user->id}/id_front.jpg",
                'id_back_path' => "verifications/{$user->id}/id_back.jpg",
                'selfie_path' => "verifications/{$user->id}/selfie.jpg",
                'status' => 'pending',
            ]);
        }

        // One approved and one rejected
        $verifiedUser = User::whereHas('profile', fn($q) => $q->where('is_verified', true))->where('role', 'user')->first();
        if ($verifiedUser) {
            VerificationRequest::create([
                'user_id' => $verifiedUser->id,
                'id_front_path' => "verifications/{$verifiedUser->id}/id_front.jpg",
                'id_back_path' => "verifications/{$verifiedUser->id}/id_back.jpg",
                'selfie_path' => "verifications/{$verifiedUser->id}/selfie.jpg",
                'status' => 'approved',
                'reviewed_by' => 1,
                'reviewed_at' => now()->subDays(3),
            ]);
        }

        $rejectedUser = User::whereHas('profile', fn($q) => $q->where('is_verified', false))->where('role', 'user')->first();
        if ($rejectedUser) {
            VerificationRequest::create([
                'user_id' => $rejectedUser->id,
                'id_front_path' => "verifications/{$rejectedUser->id}/id_front.jpg",
                'id_back_path' => "verifications/{$rejectedUser->id}/id_back.jpg",
                'selfie_path' => "verifications/{$rejectedUser->id}/selfie.jpg",
                'status' => 'rejected',
                'rejection_reason' => 'ID document is blurry and unreadable. Please retake with better lighting.',
                'reviewed_by' => 1,
                'reviewed_at' => now()->subDays(1),
            ]);
        }
    }
}
