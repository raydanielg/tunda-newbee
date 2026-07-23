import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/providers/match_provider.dart';
import '../../../models/app_models.dart';

class MatchesScreen extends StatefulWidget {
  const MatchesScreen({super.key});

  @override
  State<MatchesScreen> createState() => _MatchesScreenState();
}

class _MatchesScreenState extends State<MatchesScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsFlutterBinding.ensureInitialized().addPostFrameCallback((_) {
      context.read<MatchProvider>().loadMatches();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgLight,
      appBar: AppBar(
        title: const Text('Matches'),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: Consumer<MatchProvider>(
        builder: (context, provider, _) {
          if (provider.loading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (provider.error != null) {
            return _buildError(provider.error!, provider.loadMatches);
          }
          if (provider.matches.isEmpty) {
            return _buildEmpty();
          }
          final newMatches = provider.matches.where((m) =>
            m.lastMessage == null).toList();
          final allMatches = provider.matches.where((m) =>
            m.lastMessage != null).toList();

          return RefreshIndicator(
            onRefresh: provider.loadMatches,
            child: ListView(
              padding: const EdgeInsets.only(bottom: 24),
              children: [
                if (newMatches.isNotEmpty) ...[
                  Padding(
                    padding: const EdgeInsets.fromLTRB(20, 12, 20, 12),
                    child: Text(
                      'New Matches',
                      style: GoogleFonts.nunito(
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                        color: AppColors.gray700,
                      ),
                    ),
                  ),
                  SizedBox(
                    height: 120,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: newMatches.length,
                      itemBuilder: (context, index) =>
                        _newMatchCard(newMatches[index]),
                    ),
                  ),
                ],
                Padding(
                  padding: const EdgeInsets.fromLTRB(20, 20, 20, 12),
                  child: Text(
                    'All Matches',
                    style: GoogleFonts.nunito(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: AppColors.gray700,
                    ),
                  ),
                ),
                if (allMatches.isEmpty && newMatches.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.all(20),
                    child: Text(
                      'Start a conversation with your new matches!',
                      style: TextStyle(color: AppColors.gray400, fontSize: 14),
                    ),
                  )
                else
                  ...allMatches.map((m) => _matchTile(m)),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _newMatchCard(Match match) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      child: Column(
        children: [
          Stack(
            children: [
              Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(20),
                  gradient: const LinearGradient(
                    colors: [AppColors.maroon400, AppColors.maroon500],
                  ),
                ),
                child: match.user.avatar != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(20),
                        child: Image.network(match.user.avatar!, fit: BoxFit.cover),
                      )
                    : Center(
                        child: Text(
                          match.user.name.substring(0, 1).toUpperCase(),
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 28,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ),
              ),
              if (match.unreadCount > 0)
                Positioned(
                  right: 0,
                  child: Container(
                    padding: const EdgeInsets.all(4),
                    decoration: const BoxDecoration(
                      color: AppColors.amber500,
                      shape: BoxShape.circle,
                    ),
                    constraints: const BoxConstraints(minWidth: 20, minHeight: 20),
                    child: Text(
                      '${match.unreadCount}',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 10,
                        fontWeight: FontWeight.w700,
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(height: 6),
          Text(
            match.user.name,
            style: GoogleFonts.nunito(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: AppColors.gray600,
            ),
          ),
        ],
      ),
    );
  }

  Widget _matchTile(Match match) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.gray100),
      ),
      child: Row(
        children: [
          Stack(
            children: [
              Container(
                width: 56,
                height: 56,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  gradient: const LinearGradient(
                    colors: [AppColors.maroon400, AppColors.maroon500],
                  ),
                ),
                child: match.user.avatar != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(16),
                        child: Image.network(match.user.avatar!, fit: BoxFit.cover),
                      )
                    : Center(
                        child: Text(
                          match.user.name.substring(0, 1).toUpperCase(),
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ),
              ),
              if (match.user.online)
                Positioned(
                  bottom: 0,
                  right: 0,
                  child: Container(
                    width: 14,
                    height: 14,
                    decoration: BoxDecoration(
                      color: Colors.green,
                      shape: BoxShape.circle,
                      border: Border.all(color: Colors.white, width: 2),
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      match.user.name,
                      style: GoogleFonts.nunito(
                        fontSize: 15,
                        fontWeight: FontWeight.w700,
                        color: AppColors.gray700,
                      ),
                    ),
                    if (match.user.verified) ...[
                      const SizedBox(width: 4),
                      const Icon(Icons.verified, size: 14, color: AppColors.maroon400),
                    ],
                  ],
                ),
                const SizedBox(height: 2),
                Text(
                  match.lastMessage ?? 'Say hi!',
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: TextStyle(
                    fontSize: 13,
                    color: match.unreadCount > 0
                        ? AppColors.maroon400
                        : AppColors.gray400,
                    fontWeight: match.unreadCount > 0
                        ? FontWeight.w600
                        : FontWeight.w400,
                  ),
                ),
              ],
            ),
          ),
          if (match.unreadCount > 0)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: AppColors.maroon400,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                '${match.unreadCount}',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildEmpty() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.favorite_border, size: 64, color: AppColors.gray300),
          const SizedBox(height: 16),
          const Text(
            'No matches yet',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700, color: AppColors.gray500),
          ),
          const SizedBox(height: 8),
          const Text(
            'Start swiping to find your match!',
            style: TextStyle(fontSize: 14, color: AppColors.gray400),
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
          Text(error, textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 14, color: AppColors.gray500)),
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
