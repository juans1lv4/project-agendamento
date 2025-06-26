import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class TelaInicio extends StatelessWidget {
  const TelaInicio({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Tela de Início'),
        centerTitle: true,
      ),
      body: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ElevatedButton(
              onPressed: () async {
                
                var url = Uri.parse('http://10.0.2.2:8000/api/horarios-disponiveis');
                var response = await http.get(url);

                if (response.statusCode == 200) {
                  Navigator.pushNamed(context, 'novo_agendamento');
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Erro ao buscar horários')),
                  );
                }
              },
              child: const Text('Entrar como Cliente'),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                Navigator.pushNamed(context, '/');
              },
              child: const Text('Entrar como Administrador'),
            ),
          ],
        ),
      ),
    );
  }
}
