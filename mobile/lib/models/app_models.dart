class UserProfile {
  final String id;
  final String name;
  final int age;
  final String bio;
  final String occupation;
  final String education;
  final String region;
  final double distance;
  final List<String> interests;
  final String relationshipGoal;
  final bool verified;
  final bool online;
  final String? avatar;

  UserProfile({
    required this.id,
    required this.name,
    required this.age,
    required this.bio,
    required this.occupation,
    required this.education,
    required this.region,
    required this.distance,
    required this.interests,
    required this.relationshipGoal,
    this.verified = false,
    this.online = false,
    this.avatar,
  });
}

class ChatMessage {
  final String id;
  final String senderId;
  final String text;
  final DateTime time;
  final bool isMe;
  final bool read;

  ChatMessage({
    required this.id,
    required this.senderId,
    required this.text,
    required this.time,
    required this.isMe,
    this.read = false,
  });
}

class Match {
  final UserProfile user;
  final DateTime matchedAt;
  final String? lastMessage;
  final DateTime? lastMessageTime;
  final int unreadCount;

  Match({
    required this.user,
    required this.matchedAt,
    this.lastMessage,
    this.lastMessageTime,
    this.unreadCount = 0,
  });
}

class AppNotification {
  final String id;
  final String type;
  final String title;
  final String body;
  final DateTime time;
  final bool read;

  AppNotification({
    required this.id,
    required this.type,
    required this.title,
    required this.body,
    required this.time,
    this.read = false,
  });
}
