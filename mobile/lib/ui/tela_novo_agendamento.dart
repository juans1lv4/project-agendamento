import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:mobile/services/api_service.dart';
import 'package:table_calendar/table_calendar.dart';

class TelaNovoAgendamento extends StatefulWidget {
  const TelaNovoAgendamento({Key? key}) : super(key: key);

  @override
  _TelaNovoAgendamentoState createState() => _TelaNovoAgendamentoState();
}

class _TelaNovoAgendamentoState extends State<TelaNovoAgendamento> {
  DateTime _selectedDay = DateTime.now();
  String? _selectedTime;
  List<String> _horariosDisponiveis = [];
  bool _isLoading = false;

  final TextEditingController _nomeController = TextEditingController();
  final TextEditingController _telefoneController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    _carregarHorariosDisponiveis();
  }

  Future<void> _carregarHorariosDisponiveis() async {
    setState(() => _isLoading = true);
    final dataFormatada =
        "${_selectedDay.year}-${_selectedDay.month.toString().padLeft(2, '0')}-${_selectedDay.day.toString().padLeft(2, '0')}";
    final horarios = await ApiService.getHorariosDisponiveis(dataFormatada);
    setState(() {
      _horariosDisponiveis = horarios;
      _isLoading = false;
    });
  }

  Future<void> _confirmAgendamento() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedTime == null) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Selecione um horário!')));
      return;
    }

    setState(() => _isLoading = true);
    try {
      final agendamento = await ApiService.criarAgendamento(
        data: _selectedDay,
        horario: _selectedTime ?? '00:00', // Garante não-nulo
        nameClient: _nomeController.text,
        phoneClient: _telefoneController.text,
      );

      if (agendamento != null) {
        // Sucesso
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              'Agendamento confirmado para ${DateFormat('dd/MM/yyyy HH:mm').format(agendamento.dataHorario)}',
            ),
          ),
        );
        _nomeController.clear();
        _telefoneController.clear();
        _selectedTime = null;
        _horariosDisponiveis = [];
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Agendamento criado, mas sem resposta detalhada'),
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Erro: ${e.toString().replaceAll('Null', 'Dados faltando')}',
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Novo Agendamento'),
        actions: [
          if (_isLoading)
            const Padding(
              padding: EdgeInsets.all(8.0),
              child: CircularProgressIndicator(),
            ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
             
              TextFormField(
                controller: _nomeController,
                decoration: const InputDecoration(
                  labelText: 'Nome do Cliente',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Digite o nome';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _telefoneController,
                decoration: const InputDecoration(
                  labelText: 'Telefone (com DDD)',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.phone,
                validator: (value) {
                  if (value == null || value.length < 11) {
                    return 'Digite um telefone válido';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 20),

              // Calendário
              Card(
                elevation: 4,
                child: TableCalendar(
                  locale: 'pt_BR',
                  firstDay: DateTime.now(),
                  lastDay: DateTime.now().add(const Duration(days: 60)),
                  focusedDay: _selectedDay,
                  selectedDayPredicate: (day) => isSameDay(_selectedDay, day),
                  onDaySelected: (selectedDay, focusedDay) {
                    setState(() {
                      _selectedDay = selectedDay;
                      _selectedTime = null;
                    });
                    _carregarHorariosDisponiveis();
                  },
                  headerStyle: const HeaderStyle(
                    formatButtonVisible: false,
                    titleCentered: true,
                  ),
                ),
              ),
              const SizedBox(height: 20),
              const Text(
                'Horários Disponíveis:',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 10),

             
              Expanded(
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : _horariosDisponiveis.isEmpty
                    ? const Center(child: Text('Nenhum horário disponível'))
                    : ListView.builder(
                        itemCount: _horariosDisponiveis.length,
                        itemBuilder: (context, index) {
                          final horario = _horariosDisponiveis[index];
                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 4),
                            color: _selectedTime == horario
                                ? Colors.blue[50]
                                : null,
                            child: ListTile(
                              title: Text(horario),
                              onTap: () =>
                                  setState(() => _selectedTime = horario),
                              trailing: _selectedTime == horario
                                  ? const Icon(Icons.check, color: Colors.green)
                                  : null,
                            ),
                          );
                        },
                      ),
              ),

              
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _confirmAgendamento,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: const Text(
                    'Confirmar Agendamento',
                    style: TextStyle(fontSize: 18),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
