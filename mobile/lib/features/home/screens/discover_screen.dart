import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/providers/discover_provider.dart';
import '../../../models/app_models.dart';
import '../widgets/profile_card.dart';

class DiscoverScreen extends StatefulWidget {
  const DiscoverScreen({super.key});

  @override
  State<DiscoverScreen> createState() => _DiscoverScreenState();
}

class _DiscoverScreenState extends State<DiscoverScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsFlutterBinding.ensureInitialized().addPostFrameCallback((_) {
      context.read<DiscoverProvider>().loadProfiles();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgLight,
      appBar: AppBar(
        title: const Text('Discover'),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.tune, color: AppColors.maroon400),
            onPressed: () {},
          ),
        ],
      ),
      body: Consumer<DiscoverProvider>(
        builder: (context, provider, _) {
          if (provider.loading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (provider.error != null) {
            return _buildError(provider.error!, provider.loadProfiles);
          }
          if (provider.profiles.isEmpty) {
            return _buildEmpty();
          }
          return _buildCardStack(provider);
        },
      ),
    );
  }

  Widget _buildCardStack(DiscoverProvider provider) {
    return Column(
      children: [
        Expanded(
          child: Stack(
            children: provider.profiles.asMap().entries.toList().reversed.map((entry) {
              final index = entry.key;
              final profile = entry.value;
              final isTop = index == provider.profiles.length - 1;
              return Positioned(
                top: isTop ? 0 : (index * 12.0),
                left: isTop ? 0 : (index * 6.0),
                right: isTop ? 0 : (index * 6.0),
                bottom: isTop ? 0 : (index * 12.0),
                child: ProfileCard(
                  profile: profile,
                  isTop: isTop,
                  onLike: () => provider.swipe(profile.id, 'like'),
                  onDislike: () => provider.swipe(profile.id, 'dislike'),
                  onSuperLike: () => provider.swipe(profile.id, 'super_like'),
                ),
              );
            }).toList(),
          ),
        ),
        _buildActionButtons(provider),
      ],
    );
  }

  Widget _buildActionButtons(DiscoverProvider provider) {
    final profile = provider.profiles.isNotEmpty ? provider.profiles.last : null;
    if (profile == null) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.fromLTRB(32, 12, 32, 24),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          _actionButton(
            icon: Icons.close,
            color: AppColors.red400,
            size: 28,
            onTap: () => provider.swipe(profile.id, 'dislike'),
          ),
          _actionButton(
            icon: Icons.star,
            color: AppColors.amber500,
            size: 24,
            onTap: () => provider.swipe(profile.id, 'super_like'),
          ),
          _actionButton(
            icon: Icons.favorite,
            color: AppColors.maroon400,
            size: 32,
            onTap: () => provider.swipe(profile.id, 'like'),
          ),
        ],
      ),
    );
  }

  Widget _actionButton({
    required IconData icon,
    required Color color,
    required double size,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 56,
        height: 56,
        decoration: BoxDecoration(
          color: Colors.white,
          shape: BoxShape.circle,
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.2),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Icon(icon, color: color, size: size),
      ),
    );
  }

  Widget _buildEmpty() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.search_off, size: 64, color: AppColors.gray300),
          const SizedBox(height: 16),
          const Text(
            'No more profiles',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700, color: AppColors.gray500),
          ),
          const SizedBox(height: 8),
          const Text(
            'Check back later for new people',
            style: TextStyle(fontSize: 14, color: AppColors.gray400),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: () => context.read<DiscoverProvider>().loadProfiles(),
            icon: const Icon(Icons.refresh, size: 18),
            label: const Text('Refresh'),
          ),
        ],
      ),
    );
  }

  Widget _buildError(String error, VoidCallback onRetry) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.wifi_off, size: 64, color: AppColors.gray300),
          const SizedBox(height: 16),
          Text(
            error,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 14, color: AppColors.gray500),
          ),
          const SizedBox(height: 16),
          ElevatedButton.icon(
            onPressed: onRetry,
            icon: const Icon(Icons.refresh, size: 18),
            label: const Text('Retry'),
          ),
        ],
      ),
    );
  }
}
