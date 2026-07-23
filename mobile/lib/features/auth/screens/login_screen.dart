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

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();
  bool _obscure = true;
  String? _error;

  @override
  void dispose() {
    _emailCtrl.dispose();
    _passwordCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _error = null);

    final provider = context.read<AuthProvider>();
    final err = await provider.login(_emailCtrl.text, _passwordCtrl.text);

    if (!mounted) return;
    if (err != null) {
      setState(() => _error = err);
    } else {
      Navigator.pushReplacementNamed(context, AppRoutes.home);
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
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const AuthHeader(
                    title: 'Welcome Back',
                    subtitle: 'Sign in to your Tunda account',
                  ),
                  Padding(
                    padding: const EdgeInsets.all(32),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        AuthTextField(
                          label: 'Email Address',
                          hint: 'you@example.com',
                          icon: Icons.email_outlined,
                          keyboardType: TextInputType.emailAddress,
                          onChanged: (v) => _emailCtrl.text = v,
                        ),
                        const SizedBox(height: 18),
                        AuthTextField(
                          label: 'Password',
                          hint: 'Enter your password',
                          icon: Icons.lock_outline,
                          obscure: _obscure,
                          suffix: IconButton(
                            icon: Icon(
                              _obscure
                                  ? Icons.visibility_off_outlined
                                  : Icons.visibility_outlined,
                              size: 20,
                              color: AppColors.gray400,
                            ),
                            onPressed: () =>
                                setState(() => _obscure = !_obscure),
                          ),
                          onChanged: (v) => _passwordCtrl.text = v,
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
                        const SizedBox(height: 24),
                        PrimaryButton(
                          label: 'Sign In',
                          icon: Icons.login,
                          loading: loading,
                          onPressed: _submit,
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
                                "Don't have an account? ",
                                style: TextStyle(
                                  fontSize: 14,
                                  color: AppColors.gray500,
                                ),
                              ),
                              GestureDetector(
                                onTap: () => Navigator.pushNamed(
                                  context,
                                  AppRoutes.register,
                                ),
                                child: const Text(
                                  'Create one',
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
      ),
    );
  }
}
