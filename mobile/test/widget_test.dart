import 'package:flutter_test/flutter_test.dart';
import 'package:mobile/main.dart';

void main() {
  testWidgets('App renders splash screen', (WidgetTester tester) async {
    await tester.pumpWidget(const TundaApp());
    await tester.pump();
    expect(find.text('TUNDA'), findsOneWidget);
  });
}