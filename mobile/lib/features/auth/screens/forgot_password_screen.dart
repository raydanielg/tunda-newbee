import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../widgets/auth_background.dart';
import '../widgets/auth_card.dart';
import '../widgets/auth_header.dart';
import '../widgets/auth_text_field.dart';
import '../widgets/primary_button.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/routes/app_routes.dart';
import '../../../core/providers/auth_provider.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final _emailCtrl = TextEditingController();
  bool _sent = false;
  String? _error;

  @override
  void dispose() {
    _emailCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    setState(() => _error = null);
    final provider = context.read<AuthProvider>();
    final err = await provider.forgotPassword(_emailCtrl.text);

    if (!mounted) return;
    if (err != null) {
      setState(() => _error = err);
    } else {
      setState(() => _sent = true);
    }
  }

  @override
  Widget build(BuildContext context) {
    final loading = context.watch<AuthProvider>().isLoading;
    return Scaffold(
      body: AuthBackground(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 40),
          child: AuthCard(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                const AuthHeader(
                  title: 'Reset Password',
                  subtitle: "We'll send you a reset link",
                ),
                Padding(
                  padding: const EdgeInsets.all(32),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (_sent)
                        Container(
                          width: double.infinity,
                          padding: const EdgeInsets.all(14),
                          decoration: BoxDecoration(
                            color: AppColors.maroon50,
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: AppColors.maroon100),
                          ),
                          child: Row(
                            children: const [
                              Icon(Icons.check_circle_outline,
                                  size: 20, color: AppColors.maroon400),
                              SizedBox(width: 10),
                              Expanded(
                                child: Text(
                                  'A reset link has been sent to your email.',
                                  style: TextStyle(
                                    fontSize: 13,
                                    color: AppColors.maroon400,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        )
                      else ...[
                        AuthTextField(
                          label: 'Email Address',
                          hint: 'you@example.com',
                          icon: Icons.email_outlined,
                          keyboardType: TextInputType.emailAddress,
                          onChanged: (v) => _emailCtrl.text = v,
                        ),
                        if (_error != null) ...[
                          const SizedBox(height: 12),
                          Container(
                            width: double.infinity,
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: AppColors.red100,
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: AppColors.red300),
                            ),
                            child: Text(
                              _error!,
                              style: const TextStyle(
                                fontSize: 13,
                                color: AppColors.red600,
                              ),
                            ),
                          ),
                        ],
                      ],
                      const SizedBox(height: 24),
                      PrimaryButton(
                        label: _sent ? 'Resend Link' : 'Send Reset Link',
                        icon: Icons.mail_outline,
                        loading: loading,
                        onPressed: _sent ? null : _submit,
                      ),
                      const SizedBox(height: 24),
                      Row(
                        children: const [
                          Expanded(
                            child: Divider(color: AppColors.gray200),
                          ),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 12),
                            child: Text(
                              'or',
                              style: TextStyle(
                                color: AppColors.gray400,
                                fontSize: 13,
                              ),
                            ),
                          ),
                          Expanded(
                            child: Divider(color: AppColors.gray200),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Center(
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Text(
                              'Remember your password? ',
                              style: TextStyle(
                                fontSize: 14,
                                color: AppColors.gray500,
                              ),
                            ),
                            GestureDetector(
                              onTap: () => Navigator.pushNamed(
                                context,
                                AppRoutes.login,
                              ),
                              child: const Text(
                                'Sign in',
                                style: TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w700,
                                  color: AppColors.maroon400,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
