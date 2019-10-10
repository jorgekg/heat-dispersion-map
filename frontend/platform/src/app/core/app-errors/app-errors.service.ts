import { Injectable } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AppErrorsService {

  constructor(
    public toastController: ToastController,
    private router: Router
  ) { }

  public async showMessageError(message, color) {
    const toast = await this.toastController.create({
      message: message,
      duration: 5000,
      color: color,
      position: 'top',
      buttons: [
        {
          text: 'Ok',
          role: 'cancel'
        }
      ]
    });
    toast.present();
  }

  public async showError(err) {
    let message = 'Ocorreu um erro, tente novamente mais tarde!';
    let color = 'danger';
    if (err && err.error && err.error.code) {
      if (err.error.code === 3) {
        message = 'Este e-mail já está em uso';
        color = 'warning';
      }
    }
    if (err && err.status === 403) {
      message = 'Sua sessão não é mais válida!';
      color = 'danger';
      this.router.navigate(['/login']);
      localStorage.clear();
    }
    const toast = await this.toastController.create({
      message: message,
      duration: 5000,
      color: color,
      position: 'top',
      buttons: [
        {
          text: 'Ok',
          role: 'cancel'
        }
      ]
    });
    toast.present();
  }
}
