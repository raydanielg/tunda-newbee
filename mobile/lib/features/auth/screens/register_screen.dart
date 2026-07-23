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

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();
  final _confirmCtrl = TextEditingController();
  bool _obscure = true;
  bool _confirmObscure = true;
  String? _error;

  @override
  void dispose() {
    _nameCtrl.dispose();
    _emailCtrl.dispose();
    _passwordCtrl.dispose();
    _confirmCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_passwordCtrl.text != _confirmCtrl.text) {
      setState(() => _error = 'Passwords do not match');
      return;
    }
    setState(() => _error = null);

    final provider = context.read<AuthProvider>();
    final err = await provider.register(
      _nameCtrl.text,
      _emailCtrl.text,
      _passwordCtrl.text,
      _confirmCtrl.text,
    );

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
                    title: 'Create Account',
                    subtitle: 'Join the Tunda system',
                  ),
                  Padding(
                    padding: const EdgeInsets.all(32),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        AuthTextField(
                          label: 'Full Name',
                          hint: 'John Doe',
                          icon: Icons.person_outline,
                          keyboardType: TextInputType.name,
                          onChanged: (v) => _nameCtrl.text = v,
                        ),
                        const SizedBox(height: 18),
                        AuthTextField(
                          label: 'Email Address',
                          hint: 'name@example.com',
                          icon: Icons.email_outlined,
                          keyboardType: TextInputType.emailAddress,
                          onChanged: (v) => _emailCtrl.text = v,
                        ),
                        const SizedBox(height: 18),
                        AuthTextField(
                          label: 'Password',
                          hint: 'Min. 8 characters',
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
                        const SizedBox(height: 18),
                        AuthTextField(
                          label: 'Confirm Password',
                          hint: 'Re-enter your password',
                          icon: Icons.lock_outline,
                          obscure: _confirmObscure,
                          suffix: IconButton(
                            icon: Icon(
                              _confirmObscure
                                  ? Icons.visibility_off_outlined
                                  : Icons.visibility_outlined,
                              size: 20,
                              color: AppColors.gray400,
                            ),
                            onPressed: () => setState(
                              () => _confirmObscure = !_confirmObscure,
                            ),
                          ),
                          onChanged: (v) => _confirmCtrl.text = v,
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
                          label: 'Create Account',
                          icon: Icons.person_add_outlined,
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
                                'Already have an account? ',
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
      ),
    );
  }
}
