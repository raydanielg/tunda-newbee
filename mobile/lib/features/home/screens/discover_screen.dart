import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/constants/app_colors.dart';
import '../../../models/app_models.dart';
import '../../../models/dummy_data.dart';
import '../widgets/profile_card.dart';

class DiscoverScreen extends StatefulWidget {
  const DiscoverScreen({super.key});

  @override
  State<DiscoverScreen> createState() => _DiscoverScreenState();
}

class _DiscoverScreenState extends State<DiscoverScreen> {
  late List<UserProfile> _profiles;
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    _profiles = DummyData.profiles;
  }

  void _nextProfile() {
    if (_currentIndex < _profiles.length - 1) {
      setState(() => _currentIndex++);
    } else {
      setState(() {
        _currentIndex = 0;
        _profiles.shuffle();
      });
    }
  }

  void _like() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('You liked ${_profiles[_currentIndex].name}!'),
        backgroundColor: AppColors.maroon400,
        behavior: SnackBarBehavior.floating,
        duration: const Duration(seconds: 1),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
    _nextProfile();
  }

  void _dislike() => _nextProfile();

  void _superLike() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Super liked ${_profiles[_currentIndex].name}! ⭐'),
        backgroundColor: AppColors.amber500,
        behavior: SnackBarBehavior.floating,
        duration: const Duration(seconds: 1),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
    _nextProfile();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgLight,
      appBar: AppBar(
        title: Text(
          'Discover',
          style: GoogleFonts.nunito(
            fontWeight: FontWeight.w800,
            fontSize: 20,
          ),
        ),
        backgroundColor: AppColors.white,
        foregroundColor: AppColors.maroon400,
        elevation: 0,
        centerTitle: false,
        actions: [
          IconButton(
            icon: const Icon(Icons.tune, size: 22),
            onPressed: () {},
          ),
        ],
      ),
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              child: _profiles.isEmpty
                  ? const Center(child: Text('No more profiles'))
                  : ProfileCard(
                      profile: _profiles[_currentIndex],
                      onLike: _like,
                      onDislike: _dislike,
                      onSuperLike: _superLike,
                    ),
            ),
            Padding(
              padding: const EdgeInsets.only(bottom: 24, top: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  _actionButton(
                    icon: Icons.close,
                    color: AppColors.gray400,
                    size: 28,
                    onTap: _dislike,
                  ),
                  _actionButton(
                    icon: Icons.star,
                    color: AppColors.amber500,
                    size: 32,
                    onTap: _superLike,
                    isSpecial: true,
                  ),
                  _actionButton(
                    icon: Icons.favorite,
                    color: AppColors.maroon400,
                    size: 28,
                    onTap: _like,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _actionButton({
    required IconData icon,
    required Color color,
    required double size,
    required VoidCallback onTap,
    bool isSpecial = false,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: isSpecial ? 64 : 56,
        height: isSpecial ? 64 : 56,
        decoration: BoxDecoration(
          color: AppColors.white,
          shape: BoxShape.circle,
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.2),
              blurRadius: 12,
              spreadRadius: 2,
            ),
          ],
        ),
        child: Icon(icon, color: color, size: size),
      ),
    );
  }
}
