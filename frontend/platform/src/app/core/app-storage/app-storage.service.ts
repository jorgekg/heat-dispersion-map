import { Injectable } from '@angular/core';
import { Token, User } from '../entities/user/user.service';

@Injectable({
  providedIn: 'root'
})
export class AppStorageService {

  private key = 'br.com.jsis';
  private keys = {
    token: `${this.key}.token`,
    user: `${this.key}.user`
  }

  constructor() { }

  public setToken(token: Token) {
    localStorage.setItem(this.keys.token, JSON.stringify(token));
  }

  public getToken(): Token {
    const token = localStorage.getItem(this.keys.token);
    return token ? JSON.parse(token) : null;
  }

  public setUser(user: User) {
    localStorage.setItem(this.keys.user, JSON.stringify(user));
  }

  public getUser(): User {
    const user = localStorage.getItem(this.keys.user);
    return user ? JSON.parse(user) : null;
  }
}
