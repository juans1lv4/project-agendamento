import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:mobile/model/agendamento.dart';
import 'package:mobile/services/api_service.dart';

class TelaAdmin extends StatefulWidget {
  const TelaAdmin({super.key});

  @override
  State<TelaAdmin> createState() => _TelaAdminState();
}

class _TelaAdminState extends State<TelaAdmin> {
  List<dynamic> _agendamentos = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _carregarAgendamentos();
  }

  Future<void> _carregarAgendamentos() async {
    setState(() => _isLoading = true);
    final agendamentos = await ApiService.getAgendamentosAdmin();
    setState(() {
    _agendamentos = agendamentos;
    _isLoading = false;
    });
  }

  Future<void> _cancelarAgendamento(int id) async {
    final success = await ApiService.cancelarAgendamento(id);
    if (success) {
      await _carregarAgendamentos();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Agendamento cancelado com sucesso!')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Área Admin')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: _agendamentos.length,
              itemBuilder: (context, index) {
                final Agendamento agendamento = _agendamentos[index];
                if (agendamento.status != 'confirmado') {
                  //print('Status do agendamento: ${agendamento.status}');
                  return ListTile(
                    title: Text('${agendamento.nameClient} (Cancelado)'),
                    subtitle: Text(
                      '${DateFormat('dd/MM/yyyy HH:mm').format(agendamento.dataHorario)} - ${agendamento.phoneClient}',
                    ),
                    
                    enabled: false,
                    tileColor: const Color.fromARGB(255, 250, 249, 249),
                  );
                }
                return ListTile(
                  title: Text(agendamento.nameClient),
                  subtitle: Text(
                    '${DateFormat('dd/MM/yyyy HH:mm').format(agendamento.dataHorario)} - ${agendamento.phoneClient}',
                  ),
                  trailing: IconButton(
                    icon: const Icon(Icons.cancel, color: Colors.red),
                    onPressed: () => _cancelarAgendamento(agendamento.id),
                  ),
                );
              },
            ),
    );
  }
}
