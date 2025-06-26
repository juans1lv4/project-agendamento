import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:mobile/ui/tela_inicio.dart';
import 'package:mobile/ui/tela_novo_agendamento.dart';
import 'package:mobile/ui/telaLoginNomeNumero.dart';
import 'package:mobile/ui/tela_adm.dart';

void main() async {
  await dotenv.load(fileName: ".env");
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'App de Agendamento',
      localizationsDelegates: const [
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],
      supportedLocales: const [Locale('pt', 'BR')],
      initialRoute: 'inicio',
      routes: {
        '/': (context) => const TelaLoginNomeNumero(),
        'inicio': (context) => const TelaInicio(),
        'novo_agendamento': (context) => const TelaNovoAgendamento(),
        'admin': (context) => const TelaAdmin(),
      },
    );
  }
}