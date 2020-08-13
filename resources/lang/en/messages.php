<?php

return [
    'welcome' => 'Welcome to our application',

    // General
    'general' => [
        'website-title'         => ':title',
        'submit'                => 'Submit',
        "create"                => 'Create',
        "delete"                => 'Delete',
        "edit"                  => 'Edit',
        "view"                  => 'View',
        "cancel"                => 'Cancel',
        "update"                => 'Update',
        "enable"                => 'Enable',
        "disable"               => 'Disable',
        "hidden"                => 'Hidden',
        "login"                 => 'Login',
        "language"              => 'Language',
        "choose_language"       => 'Choose Language',
        "close"                 => 'Close',
        "search"                => 'Search',
        "go_back_button"        => 'Go Back',
        "no_data"               => 'No data available..',
        "yes"                   => 'Yes',
        "no"                    => 'No',
        "active"                => 'Active',
        "disabled"              => 'Disabled',
        "loading"               => 'Loading',
        "view_more"             => 'View more',
        "view_x_more"           => 'View :x more',
        "clipboard_copy"        => 'Copy to clipboard',
        "verified"              => 'Verified',

        'message_types' => [
            'error'     => 'Error!',
            'info'      => 'Info!',
            'success'   => 'Success!',
        ],

        'info_messages' => [
            'confirm_delete'               => 'Are you sure you want to delete this?',
            'user_package_is_expired'      => 'Your current package has expired and your access is limited. Please get a new package!',
        ],

        'success_message' => [
            'confirm_delete'               => 'Your requested command was performed successfully!',
            'user_package_is_expired'      => 'Your account is now active!',
        ],

        'menu' => [
            'home'          =>   'Home',
            'how-it-work'   =>   'How it works',
            'pricing'       =>   'Pricing',
            'pages'         =>   'Pages',
            'login'         =>   'Login',
            'register'      =>   'Register',
            'dashboard'     =>   'Dashboard',
        ],

        'accessibility' => [
            'logo_alt'       =>   'Website Logo',
        ],

    ],


    'email-notifications' => [
        'new_user_subject'                  =>   'Website Logo',
        'new_user_body'                     =>   'Website Logo',
        'new_payment_subject'               =>   'Website Logo',
        'new_payment_body'                  =>   'Website Logo',
        'new_support_subject'               =>   'Website Logo',
        'new_support_subject'               =>   'Website Logo',
        'new_supportreply_subject'          =>   'Website Logo',
        'new_supportreply_subject'          =>   'Website Logo',
    ],

    'emails'   =>   [
        #emails
    ],

    'pricing'   =>   [
        'breadcrumb'                => 'Pricing',
        'heading'                   => 'Pricing',
        'sub-heading'               => 'Best Pricing Package Start <br> Business',
        'sign-up-text'              => 'Choose',
    ],
    # Page

    # Manage Home


    // Home
    'home' => [
    'home-title'                                => 'Advanced Bio Link System for all Social Media.',
    'home-title-p'                              => 'Create and manage portfolio, links and link shortening. A unique space with one shareable link for your Instagram, Facebook, Tik Tok and LinkedIn profile.',
    'pills-section' => [
        'title-sub-title'                       => 'Ease of use',
        'title-heading'                         => 'Easy Management Across Devices',
        'pills-1-title'                         =>  'Advanced Statistics',
        'pills-1-des'                           =>  'See daily visits, hits, location, operating system, browser info of page visitors and more.',
        'pills-2-title'                         =>  'Link Shortening',
        'pills-2-des'                           =>  'Shorten your links on the go and share across Instagram, Facebook, Tik Tok and LinkedIn.',
        'pills-3-title'                         =>  'Get Support',
        'pills-3-des'                           =>  'Get in touch for support right from your dashboard. Send and receive support emails instantly.',
    ],

    'how-it-work-subtitle'                      => 'How it works',
    'how-it-work-title-heading'                 => 'Let’s get started in 3 easy <br> steps',
    'how-it-work' => [
    'section-1-title'                           => 'Create Account',
    'section-1-des'                             => 'Simply sign up to get started with creating a beautiful and simple interface for your portfolio and links.',
    'section-2-title'                           => 'Easy Setup', 
    'section-2-des'                             => 'Create items you want to showcase on your portfolio or set your all-important links as much as you need.',
    'section-3-title'                           => 'Start Sharing',
    'section-3-des'                             => 'Share your profile link on Instagram, Facebook, Tik Tok, LinkedIn, anywhere and boom, that’s it!'
    ],

    'menu' => [
        'home-text'                             => 'Home',
        'how-it-work-text'                      => 'How it works',
        'pricing-text'                          => 'Pricing',
        'contact-text'                          => 'Contact',
        'login-text'                            => 'Login',
        'register-text'                         => 'Sign Up'
        ],
    ],

    // Login
    'login' => [
    'forgot-password'                            => 'Forgot Password?',
    'login-btn'                                  => 'Sign in',
    'head-block-text'                            => 'Use the form below to login to our dashboard',
    'create-account'                             => 'Are you new here?',
    'create-account-btn'                         => 'Create account',
    'confirm' => [
        'heading' => 'Reset Password',
        'sub-heading'   => 'Use the form below to reset your password',
        ],
    ],

    // Emails
    'emails' => [
        'tagline'                   => 'The unique profile link',
        'activate-email' => [
            'new-account-text'     => 'Your new account is ready',
            'name-text'            => 'Hi <br>:name</b>',
            'welcome-text'         => 'Welcome! <br> You are receiving this email because you have registerd on our site.',
            'copy-text'            => 'Click or copy the link below to activate your account.',
        ],
    ],


    // Register
    'register' => [
        'return-to-login'                   => 'Have an account?',
        'return-to-login-btn'               => 'Login',
        'head-block-text'                   => 'Sign up to our platform!',
    ],


    'errors' => [
        'not-found' => 'Not Found',
    ],
    'dashboard' => ['welcome'   => 'hey']
];