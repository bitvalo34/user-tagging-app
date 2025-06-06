#!/usr/bin/env python
# -*- coding: utf-8 -*-
import tkinter as tk
from tkinter import ttk, messagebox
import serial, threading, requests, sys, datetime

# ───────── CONFIGURACIÓN ────────────────────────────────────────── #
UIDS_VALIDOS = {
    "5370D724", "0295AD05", "A64EAD05", "FBD6AB05", "72B8AE05",
    "FE2FAE05", "214EAE05", "2419AC05", "5170A905", "6DA1B405"
}
URL_POST = "http://localhost/Proyecto 2 CC5/marcas/insertar_marca.php"
SERIAL_PORT = 'COM7'
SERIAL_BAUD = 9600
# ────────────────────────────────────────────────────────────────── #

class RegistroApp(tk.Tk):
    def __init__(self):
        super(RegistroApp, self).__init__()
        self.title("Registro de Asistencia - SIS")
        self.geometry("440x260")
        self.configure(bg="#f5f7fa")
        self.resizable(False, False)
        self.protocol("WM_DELETE_WINDOW", self._cerrar)

        # — Tema ttk
        style = ttk.Style(self) 
        style.theme_use('clam')
        style.configure('TButton',
                        font=('Open Sans', 11, 'bold'),
                        padding=10)
        style.configure('Primary.TButton',
                        background='#0d47a1', foreground='white')
        style.map('Primary.TButton',
                  background=[('active', '#134fae')])
        style.configure('Danger.TButton',
                        background='#c62828', foreground='white')
        style.map('Danger.TButton',
                  background=[('active', '#d33b3b')])

        # tipo_marca
        self.tipo_marca = tk.StringVar(value="entrada")

        # Widgets UI
        ttk.Label(self, text="Seleccione tipo de marca",
                  font=('Montserrat', 14, 'bold'),
                  background='#f5f7fa', foreground='#0d47a1').pack(pady=(15, 5))

        frame_btn = ttk.Frame(self)
        frame_btn.pack()

        self.btn_entrada = ttk.Button(frame_btn,
                                      text="Entrada",
                                      style='Primary.TButton',
                                      command=lambda: self._select_tipo('entrada'))
        self.btn_entrada.grid(row=0, column=0, padx=15)

        self.btn_salida = ttk.Button(frame_btn,
                                     text="Salida",
                                     style='Danger.TButton',
                                     command=lambda: self._select_tipo('salida'))
        self.btn_salida.grid(row=0, column=1, padx=15)

        ttk.Separator(self, orient='horizontal').pack(fill='x', pady=15)

        self.lbl_result = ttk.Label(self, text="", font=('Open Sans', 12),
                                    background='#f5f7fa')
        self.lbl_result.pack()

        # Estado de conexión serial
        self.lbl_estado = ttk.Label(self,
                                    text=f"Puerto {SERIAL_PORT}…",
                                    font=('Open Sans', 9),
                                    foreground='#808080',
                                    background='#f5f7fa')
        self.lbl_estado.pack(side='bottom', pady=4)

        # Hilo Serial
        try:
            self.ser = serial.Serial(SERIAL_PORT, SERIAL_BAUD, timeout=1)
            self.lbl_estado.config(text=f"Conectado a {SERIAL_PORT}")
            threading.Thread(target=self._leer_serial,
                             daemon=True).start()
        except serial.SerialException:
            self.lbl_estado.config(text=f"⚠ No se pudo abrir {SERIAL_PORT}")

    # ─── EVENTOS ────────────────────────────────────────────── #
    def _select_tipo(self, tipo):
        self.tipo_marca.set(tipo)
        # Cambiar estilos visuales
        if tipo == "entrada":
            self.btn_entrada.configure(style='Primary.TButton')
            self.btn_salida.configure(style='TButton')
        else:
            self.btn_entrada.configure(style='TButton')
            self.btn_salida.configure(style='Danger.TButton')

    def _leer_serial(self):
        while True:
            try:
                if self.ser.in_waiting:
                    uid = self.ser.readline().decode('utf-8').strip().upper()
                    if uid:
                        self.after(0, self._procesar_uid, uid)
            except Exception as e:
                # Problema con el puerto en caliente
                self.after(0, self.lbl_estado.config,
                           {'text': f"Error serial: {e}"})
                break

    def _procesar_uid(self, uid):
        def limpiar(): self.lbl_result.config(text="")
        if uid in UIDS_VALIDOS:
            payload = {'uid': uid, 'tipo_marca': self.tipo_marca.get()}
            try:
                r = requests.post(URL_POST, data=payload, timeout=3)
                if r.status_code == 200:
                    if self.tipo_marca.get() == 'entrada':
                        self.lbl_result.config(text="¡Bienvenido!",
                                               foreground='#2e7d32')
                    else:
                        self.lbl_result.config(text="¡Hasta luego!",
                                               foreground='#1565c0')
                else:
                    self.lbl_result.config(text="Error al registrar (servidor)",
                                           foreground='#ff6f00')
            except Exception as e:
                self.lbl_result.config(text="Error de red",
                                       foreground='#ff6f00')
        else:
            self.lbl_result.config(text="Acceso denegado",
                                   foreground='#c62828')

        # Limpiar después de 3 s
        self.after(3000, limpiar)

    def _cerrar(self):
        if messagebox.askokcancel("Salir", "¿Cerrar la aplicación?"):
            try:
                if hasattr(self, 'ser') and self.ser.is_open:
                    self.ser.close()
            except:
                pass
            self.destroy()

# ───────── RUN ─────────────────────────────────────────────── #
if __name__ == '__main__':
    RegistroApp().mainloop()

