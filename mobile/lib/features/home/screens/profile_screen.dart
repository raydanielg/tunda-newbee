import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/routes/app_routes.dart';
import '../../../core/providers/auth_provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgLight,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              Container(
                width: double.infinity,
                padding: const EdgeInsets.fromLTRB(24, 32, 24, 32),
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [AppColors.maroon400, AppColors.maroon600],
                  ),
                  borderRadius: BorderRadius.only(
                    bottomLeft: Radius.circular(32),
                    bottomRight: Radius.circular(32),
                  ),
                ),
                child: Column(
                  children: [
                    Container(
                      width: 90,
                      height: 90,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: AppColors.white.withOpacity(0.1),
                        border: Border.all(
                          color: AppColors.white.withOpacity(0.3),
                          width: 2,
                        ),
                      ),
                      child: const Icon(Icons.person,
                          size: 40, color: AppColors.white),
                    ),
                    const SizedBox(height: 14),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'John Doe, 28',
                          style: GoogleFonts.nunito(
                            fontSize: 22,
                            fontWeight: FontWeight.w800,
                            color: AppColors.white,
                          ),
                        ),
                        const SizedBox(width: 6),
                        const Icon(Icons.verified,
                            size: 18, color: AppColors.amber400),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      'Software Engineer · Dar es Salaam',
                      style: GoogleFonts.nunito(
                        fontSize: 13,
                        color: AppColors.maroon100,
                      ),
                    ),
                    const SizedBox(height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        _stat('128', 'Matches'),
                        _stat('56', 'Likes'),
                        _stat('12', 'Super'),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Column(
                  children: [
                    _menuTile(
                      icon: Icons.person_outline,
                      title: 'Edit Profile',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.camera_alt_outlined,
                      title: 'Manage Photos',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.verified_outlined,
                      title: 'Profile Verification',
                      badge: 'Pending',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.workspace_premium_outlined,
                      title: 'Premium Membership',
                      badge: 'PRO',
                      badgeColor: AppColors.amber500,
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.account_balance_wallet_outlined,
                      title: 'Wallet',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.rocket_launch_outlined,
                      title: 'Boosts & Super Likes',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.shield_outlined,
                      title: 'Safety & Privacy',
                      onTap: () {},
                    ),
                    _menuTile(
                      icon: Icons.settings_outlined,
                      title: 'Settings',
                      onTap: () {},
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      child: OutlinedButton.icon(
                        onPressed: () async {
                          await context.read<AuthProvider>().logout();
                          if (!context.mounted) return;
                          Navigator.pushNamedAndRemoveUntil(
                            context,
                            AppRoutes.welcome,
                            (route) => false,
                          );
                        },
                        icon: const Icon(Icons.logout, size: 18),
                        label: const Text('Sign Out'),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: AppColors.maroon400,
                          side: const BorderSide(color: AppColors.maroon200),
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),
                    Text(
                      'Tunda v1.0.0',
                      style: GoogleFonts.nunito(
                        fontSize: 12,
                        color: AppColors.gray400,
                      ),
                    ),
                    const SizedBox(height: 16),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _stat(String value, String label) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.nunito(
            fontSize: 22,
            fontWeight: FontWeight.w800,
            color: AppColors.white,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: GoogleFonts.nunito(
            fontSize: 12,
            color: AppColors.maroon100,
          ),
        ),
      ],
    );
  }

  Widget _menuTile({
    required IconData icon,
    required String title,
    VoidCallback? onTap,
    String? badge,
    Color? badgeColor,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      decoration: BoxDecoration(
        color: AppColors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.gray100),
      ),
      child: Material(
        color: Colors.transparent,
        child: ListTile(
        onTap: onTap,
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
        ),
        leading: Container(
          width: 38,
          height: 38,
          decoration: BoxDecoration(
            color: AppColors.maroon50,
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, size: 20, color: AppColors.maroon400),
        ),
        title: Text(
          title,
          style: GoogleFonts.nunito(
            fontSize: 14,
            fontWeight: FontWeight.w600,
            color: AppColors.gray700,
          ),
        ),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (badge != null)
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: (badgeColor ?? AppColors.gray400).withOpacity(0.15),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  badge,
                  style: GoogleFonts.nunito(
                    fontSize: 11,
                    fontWeight: FontWeight.w700,
                    color: badgeColor ?? AppColors.gray500,
                  ),
                ),
              ),
            const SizedBox(width: 4),
            const Icon(Icons.chevron_right,
                size: 20, color: AppColors.gray300),
          ],
        ),
      ),
    );
  }
}
